<?php
require_once '../Services/Autoload.php';

class CompraControl extends CrudControl {

    public function __construct(){
        $this->DAO = new CompraDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 2:
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 3:
                $this->atualizar();
                header('Location:../View/CondutorView.php');
                break;
        }
    }

    public function cadastrar()
    {
        $total = $this->calculaTotal($_POST['itens']);

        $compra = new CompraModel($_POST['proposito'],$total);
        $this->DAO->cadastrar($compra,$_POST['idTarefa']);

        $idCompra = $this->DAO->pdo->lastInsertId();

        $itemControl = new ItemControl();
        $itemControl->cadastrarPorArray($_POST['itens'],$idCompra);
    }

    public function calculaTotal(array $itens)
    {
        $total = 0;
        foreach ($itens as $item){
            $total += $item['valor'] * $item['qtd'];
        }
        return $total;
    }

    protected function excluir($id)
    {
        $itemControl = new ItemControl();
        $itemControl->excluirPorIdCompra($id);
        $this->DAO->excluir($id);
    }

    public function organizarItens(array $itens){
        $jsArray ='[';
        foreach ($itens as $item){
            $jsArray .= '["'.$item->getId().'","'.$item->getNome().'","'.$item->getValor().'","'.$item->getQuantidade().'"],';
        }
        $jsArray = trim($jsArray,',');
        $jsArray .= ']';
        return $jsArray;
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    public function listarPorIdTarefa($id):array
    {
        return $this->DAO->listarPorIdTarefa($id);
    }

    protected function atualizar()
    {
        $condutor = new CondutorModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH'],$_POST['id']);
        $this -> DAO -> atualizar($condutor);
    }
}

$class = new CompraControl();