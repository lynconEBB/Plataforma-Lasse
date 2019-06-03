<?php
require_once '../Services/Autoload.php';

class ProjetoControl extends CrudControl {

    public function __construct(){
        $this->DAO = new ProjetoDao();
        parent::__construct();
    }

    protected function cadastrar(){

        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto']);
        $this->DAO->cadastrar($projeto);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar() {
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id']);
        $this -> DAO -> alterar($projeto);
    }

    public function listarPorIdUsuario($id){
        return $this->DAO->listarPorIdUsuario($id);
    }

    public function procuraFuncionario($id){
        return $this->DAO->procuraFuncionario($id);
    }

}
LoginControl::verificar();
new ProjetoControl();