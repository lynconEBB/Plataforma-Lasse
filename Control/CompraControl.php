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
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
        }
    }

    public function cadastrar()
    {
        $total = $this->calculaTotalArray($_POST['itens']);

        $compra = new CompraModel($_POST['proposito'],$total);
        $this->DAO->cadastrar($compra,$_POST['idTarefa']);

        $idCompra = $this->DAO->pdo->lastInsertId();

        $itemControl = new ItemControl();
        $itemControl->cadastrarPorArray($_POST['itens'],$idCompra);
    }

    public function calculaTotalArray(array $itens)
    {
        $total = 0;
        foreach ($itens as $item){
            $total += $item['valor'] * $item['qtd'];
        }
        return $total;
    }

    public function calculaTotal(array $itens)
    {
        $total = 0;
        foreach ($itens as $item){
            $total += $item->getValor() * $item->getQuantidade();
        }
        return $total;
    }

    public function atualizaTotal($total,$idCompra)
    {
        $this->DAO->atualizaTotal($idCompra,$total);
    }


    protected function excluir($id)
    {
        $itemControl = new ItemControl();
        $itemControl->excluirPorIdCompra($id);
        $this->DAO->excluir($id);
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    public function listarPorIdTarefa($id):array
    {
        return $this->DAO->listarPorIdTarefa($id);
    }

    public function listarPorId($id):CompraModel
    {
        return $this->DAO->listarPorId($id);
    }

    protected function atualizar()
    {
        $compra = new CompraModel($_POST['proposito'],null,$_POST['null'],$_POST['id']);
        $this -> DAO -> atualizar($compra,$_POST['idTarefa']);
    }


    public function verificaPermissao()
    {
        if(isset($_GET['idTarefa'])){
            //Descobrindo id do Projeto em que a tarefa foi criada
            $tarefaControl = new TarefaControl();
            $idProjeto = $tarefaControl->descobrirIdProjeto($_GET['idTarefa']);

            // Verifica se o funcionário está relacionado com o Projeto
            $projetoControl = new ProjetoControl();

            if($projetoControl->procuraFuncionario($idProjeto) > 0){
                return true;
            }else{
                die("Você não possui acesso a essa Compra<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
            }
        }else{
            die("Nenhuma Tarefa Selecionada!!<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
        }
    }
}

$class = new CompraControl();