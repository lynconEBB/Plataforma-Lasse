<?php

class TarefaControl extends CrudControl {

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new TarefaDao();
        parent::__construct();
    }

    public function defineAcao($acao)
    {
        switch ($acao){
            case 'cadastrarTarefa':
                $this->cadastrar();
                break;
            case 'excluirTarefa':
                $this->excluir($_POST['id']);
                break;
            case 'alterarTarefa':
                $this->atualizar();
                break;
        }
    }

    protected function cadastrar(){
        $tarefa = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],null,null,null,null,null);
        $this->DAO->cadastrar($tarefa,$_POST['idProjeto']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        $projetoControl = new ProjetoControl();
        $projetoControl->atualizaTotal($_POST['idProjeto']);
    }

    public function listar() {
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id'],null,null,null,null);
        $this -> DAO ->atualizar($projeto);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function listarPorIdProjeto($id){
        return $this->DAO->listarPorIdProjeto($id);
    }

    public function listarPorId($id){
        return $this->DAO->listarPorId($id);
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

    public function atualizaTotal($idTarefa){
        $tarefa = $this->listarPorId($idTarefa);
        $this->DAO->atualizaTotal($tarefa);
        $idProjeto = $this->DAO->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl();
        $projetoControl->atualizaTotal($idProjeto);
    }

    public function verificaPermissao(){
        if(isset($_GET['idProjeto'])){
            $projetoControl = new ProjetoControl();
            if($projetoControl->procuraFuncionario($_GET['idProjeto'],$_SESSION['usuario-id']) > 0){
                return true;
            }else{
                require '../View/errorPages/erroSemAcesso.php';
                return false;
            }
        }else{
            require '../View/errorPages/erroNaoSelecionado.php';
            return false;
        }
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'listaTarefas':
                if($this->verificaPermissao()) {
                    $tarefas = $this->listarPorIdProjeto($_GET['idProjeto']);
                    require '../View/TarefaView.php';
                }
        }
    }
}
