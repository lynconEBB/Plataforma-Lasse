<?php
require_once '../Services/Autoload.php';

class TarefaControl extends CrudControl {

    public function __construct(){
        $this->DAO = new TarefaDao();
        parent::__construct();
    }

    protected function cadastrar(){
        $tarefa = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao']);
        $this->DAO->cadastrar($tarefa,$_POST['idProjeto']);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar() {
        //return $this -> Dao -> listar();
    }

    protected function atualizar(){
        $projeto = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id']);
        $this -> DAO ->atualizar($projeto);
    }

    public function listarPorIdProjeto($id){
        return $this->DAO->listarPorIdProjeto($id);
    }

    public function procuraProjeto(){

        if(empty($_POST['idProjeto']) && empty($_SESSION['idProjeto'])) {
            echo "<h3>Nenhum ProjetoModel Selecionado, por favor Selecione um clicando <a href='ProjetoView.php'>aqui</a></h3>";
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
