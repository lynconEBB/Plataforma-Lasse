<?php
require_once 'CrudControl.php';
require_once '../Model/Projeto.php';
require_once '../Model/ProjetoDAO.php';

class ProjetoControl extends CrudControl {

    public function __construct(){
        $this->DAO = new ProjetoDAO();
        parent::__construct();
    }


    protected function cadastrar(){
        $projeto = new Projeto($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto']);
        $this->DAO->cadastrar($projeto);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        /*$condutor = new Condutor($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH'],$_POST['id']);
        $this -> DAO -> atualizar($condutor);*/
    }
}
new ProjetoControl();