<?php
    require_once '../Services/Autoload.php';

class ItemControl extends CrudControl{

    public function __construct(){
        $this->DAO = new ItemDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarItem':
                $this->cadastrar();
                break;
            case 2:
                $this->excluir($_GET['id']);
                break;
            case 3:
                $this->atualizar();
                break;
        }
    }

    protected function cadastrar(){
        $item = new ItemModel($_POST['valor'],$_POST['nome'],$_POST['qtd']);
        $this->DAO->cadastrar($item,$_POST['idTarefa']);
    }

    public function cadastrarPorArray(array $itens, int $idCompra)
    {
        foreach ($itens as $item){
            $ite = new ItemModel($item['valor'],$item['nome'],$item['qtd']);
            $this->DAO->cadastrar($ite,$idCompra);
        }
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function excluirPorIdCompra($id){
        $this -> DAO -> excluirPorIdCompra($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    public function listarPorIdCompra($idCompra){
        return $this -> DAO -> listarPorIdCompra($idCompra);
    }

    protected function atualizar(){
        $condutor = new CondutorModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH'],$_POST['id']);
        $this -> DAO -> atualizar($condutor);
    }
}