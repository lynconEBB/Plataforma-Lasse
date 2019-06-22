<?php

class AtividadeControl extends CrudControl {

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new AtividadeDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarAtividade':
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'excluirAtividade':
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'alterarAtividade':
                $this->atualizar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;

        }
    }

    public function cadastrar()
    {
        $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe']);
        if(isset($_POST['idTarefa'])){
            $this->DAO->cadastrarPlanejado($atividade,$_POST['idTarefa']);
            $tarefaControl = new TarefaControl();
            $tarefaControl->atualizaTotal($_POST['idTarefa']);
        }else{
            $this->DAO->cadastrarPlanejado($atividade,null);
        }
    }

    protected function excluir(int $id)
    {
        $this->DAO->excluir($id);
        if(isset($_POST['idTarefa'])) {
            $tarefaControl = new TarefaControl();
            $tarefaControl->atualizaTotal($_POST['idTarefa']);
        }
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    protected function atualizar()
    {
        $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe'],$_POST['id']);
        $this -> DAO -> atualizar($atividade);
        if(isset($_POST['idTarefa'])) {
            $tarefaControl = new TarefaControl();
            $tarefaControl->atualizaTotal($_POST['idTarefa']);
        }
    }

    public function listarPorIdTarefa($id):array
    {
        return $this->DAO->listarPorIdTarefa($id);
    }

    public function listarPorIdUsuario($id):array
    {
        return $this->DAO->listarPorIdUsuario($id);
    }

    public function verificaPermissao()
    {
        if(isset($_GET['idTarefa'])){
            //Descobrindo id do Projeto em que a tarefa foi criada
            $tarefaControl = new TarefaControl();
            $idProjeto = $tarefaControl->descobrirIdProjeto($_GET['idTarefa']);

            // Verifica se o funcionário está relacionado com o Projeto
            $projetoControl = new ProjetoControl();

            if($projetoControl->procuraFuncionario($idProjeto,$_SESSION['usuario-id']) > 0){
                return true;
            }else{
                require '../View/errorPages/erroSemAcesso.php';
                exit();
            }
        }else{
            require '../View/errorPages/erroNaoSelecionado.php';
            exit();
        }
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'listaAtividadesPlanejadas':
                $this->verificaPermissao();
                $atividades = $this->listarPorIdTarefa($_GET['idTarefa']);
                require '../View/telaAtividadePlanejada.php';
                break;
            case 'listaAtividadesNaoPlanejadas':
                $atividades = $this->listarPorIdUsuario($_SESSION['usuario-id']);
                require '../View/telaAtividadeNaoPlanejada.php';
                break;
        }
    }
}