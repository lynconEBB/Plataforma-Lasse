<?php
require_once '../Services/Autoload.php';


class FuncionarioControl extends CrudControl {

    public function __construct(){
        $this->DAO = new FuncionarioDao();
        parent::__construct();
    }

    protected function cadastrar(){
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],$_POST['senha'],$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora']);
        $this->DAO->cadastrar($usuario);
    }

    protected function excluir($id){
        //$this -> Dao -> excluir($id);
    }

    public function listar() {
        //return $this -> Dao -> listar();
    }

    protected function atualizar(){
        //$projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id']);
        //$this -> Dao -> alterar($projeto);
    }
}
new FuncionarioControl();