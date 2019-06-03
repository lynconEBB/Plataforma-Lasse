<?php
require_once '../Services/Autoload.php';

class UsuarioControl extends CrudControl {

    public function __construct(){
        $this->DAO = new UsuarioDao();
        parent::__construct();
    }

    protected function cadastrar(){
        /*$tarefa = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao']);
        $this->DAO->cadastrar($tarefa,$_POST['idProjeto']);
        header('Location:../View/TarefaView.php?idProjeto='.$_POST['idProjeto']);*/
    }

    protected function excluir($id){
        /*$this -> DAO -> excluir($id);
        header('Location:../View/TarefaView.php?idProjeto='.$_POST['idProjeto']);*/
    }

    public function listar() {
        //return $this -> Dao -> listar();
    }

    protected function atualizar(){
        /*$projeto = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id']);
        $this -> DAO ->atualizar($projeto);

        header('Location:../View/TarefaView.php?idProjeto='.$_POST['id']);*/
    }

}

LoginControl::verificar();
new TarefaControl();