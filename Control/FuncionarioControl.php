<?php
require_once 'CrudControl.php';
require_once '../Model/Usuario.php';
require_once '../DAO/FuncionarioDAO.php';

class FuncionarioControl extends CrudControl {

    public function __construct(){
        $this->DAO = new FuncionarioDAO();
        parent::__construct();
    }

    protected function cadastrar(){
        $usuario = new Usuario($_POST['nome'],$_POST['usuario'],$_POST['senha'],$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora']);
        $this->DAO->cadastrar($usuario);
    }

    protected function excluir($id){
        //$this -> DAO -> excluir($id);
    }

    public function listar() {
        //return $this -> DAO -> listar();
    }

    protected function atualizar(){
        //$projeto = new Projeto($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id']);
        //$this -> DAO -> alterar($projeto);
    }
}
new ProjetoControl();