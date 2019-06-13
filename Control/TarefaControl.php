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
        header('Location:../View/TarefaView.php?idProjeto='.$_POST['idProjeto']);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
        header('Location:../View/TarefaView.php?idProjeto='.$_POST['idProjeto']);
    }

    public function listar() {
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id']);
        $this -> DAO ->atualizar($projeto);

        header('Location:../View/TarefaView.php?idProjeto='.$_POST['idProjeto']);
    }

    public function listarPorIdProjeto($id){
        return $this->DAO->listarPorIdProjeto($id);
    }

    public function descobrirIdProjeto($id){
        return $this->DAO->descobrirIdProjeto($id);
    }

    public function listarPorIdUsaurio($id){
        $listaDeTarefas = [];
        $projetoControl = new ProjetoControl();
        $projetos = $projetoControl->listarPorIdUsuario($id);
        foreach ($projetos as $projeto){
            $tarefas = $this->listarPorIdProjeto($projeto->getId());
            foreach ($tarefas as $tarefa){
                $listaDeTarefas[] = $tarefa;
            }
        }

        return $listaDeTarefas;
    }

    public function verificaPermissao(){
        if(isset($_GET['idProjeto'])){
            $projetoControl = new ProjetoControl();
            if($projetoControl->procuraFuncionario($_GET['idProjeto']) > 0){
                return true;
            }else{
                die("Você não possui acesso a essa tarefa<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
            }
        }else{
            die("Nenhuma Projeto Selecionado!!<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
        }
    }
}

LoginControl::verificar();
new TarefaControl();
