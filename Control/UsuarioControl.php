<?php
require_once '../Services/Autoload.php';

class UsuarioControl extends CrudControl {

    public function __construct(){
        $this->DAO = new UsuarioDao();
        parent::__construct();
    }

    protected function cadastrar(){
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],$_POST['senha'],$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora']);
        $this->DAO->cadastrar($usuario);

        header('Location: ../View/LoginView.php');
        die();
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
        header('Location:../View/LoginView.php');
    }

    public function listar() {
        return $this ->DAO -> listar();
    }

    protected function atualizar(){
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],null,$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora'],$_SESSION['usuario-id']);
        $this -> DAO ->alterar($usuario);

        header('Location:../View/UsuarioView.php');
    }

    public function listarPorId($id){
        return $this->DAO->listarPorId($id);
    }

}
new UsuarioControl();