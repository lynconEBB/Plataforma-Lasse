<?php
require_once 'LoginControl.php';
require_once 'CrudControl.php';
require_once '../DAO/TarefaDAO.php';

class TarefaControl extends CrudControl {

    public function __construct(){
        $this->DAO = new TarefaDAO();
        parent::__construct();
    }

    protected function cadastrar(){
        $tarefa = new Tarefa($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao']);
        $this->DAO->cadastrar($tarefa,$_POST['idProjeto']);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar() {
        //return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new Tarefa($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id']);
        $this -> DAO ->atualizar($projeto);
    }

    public function listarPorIdProjeto($id){
        return $this->DAO->listarPorIdProjeto($id);
    }

    public function procuraProjeto(){

        if(empty($_POST['idProjeto']) && empty($_SESSION['idProjeto'])) {
            echo "<h3>Nenhum Projeto Selecionado, por favor Selecione um clicando <a href='projetoView.php'>aqui</a></h3>";
            die();
        }
        elseif (isset($_POST['idProjeto']) && empty($_SESSION['idProjeto'])) {
            $_SESSION['idProjeto'] = $_POST['idProjeto'];
        }
        elseif(isset($_SESSION['idProjeto']) && isset($_POST['idProjeto'])){
            if($_SESSION['idProjeto'] != $_POST['idProjeto']){
                $_SESSION['idProjeto'] = $_POST['idProjeto'];
            }
        }
    }
}
LoginControl::verificar();
new TarefaControl();
