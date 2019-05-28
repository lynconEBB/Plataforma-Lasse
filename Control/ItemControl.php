<?php
    require_once '../Services/Autoload.php';

class ItemControl extends CrudControl{

    public function __construct(){
        $this->DAO = new ItemDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
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
        $condutor = new ItemModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH']);
        $this->DAO->cadastrar($condutor);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $condutor = new CondutorModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH'],$_POST['id']);
        $this -> DAO -> atualizar($condutor);
    }
}