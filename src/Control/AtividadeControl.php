<?php

namespace Lasse\LPM\Control;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Exception;
use Lasse\LPM\Dao\AtividadeDao;
use Lasse\LPM\Model\AtividadeModel;
use PDOException;

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
                //header('Location: ' . $_SERVER['HTTP_REFERER']);
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
        try{
            $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe'],null,null);
            if(isset($_POST['idTarefa'])){
                $this->DAO->cadastrar($atividade,$_POST['idTarefa']);
                $tarefaControl = new TarefaControl();
                $tarefaControl->atualizaTotal($_POST['idTarefa']);
            }else{
                $this->DAO->cadastrar($atividade,null);
            }
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante cadastro no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }catch (Exception $excecao){
            $_SESSION['danger'] = $excecao->getMessage();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    protected function excluir(int $id)
    {
        try{
            $this->DAO->excluir($id);
            if(isset($_POST['idTarefa'])) {
                $tarefaControl = new TarefaControl();
                $tarefaControl->atualizaTotal($_POST['idTarefa']);
            }
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }catch (Exception $excecao){
            $_SESSION['danger'] = 'Erro durante Exclusão';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

    }

    public function listar()
    {
        try{
            return $this->DAO->listar();
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

    }

    protected function atualizar()
    {
        try{
            $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe'],$_POST['id'],null);
            $this->DAO->atualizar($atividade);
            if(isset($_POST['idTarefa'])) {
                $tarefaControl = new TarefaControl();
                $tarefaControl->atualizaTotal($_POST['idTarefa']);
            }
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante alteração no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }catch (Exception $excecao){
            $_SESSION['danger'] = $excecao->getMessage();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

    }

    public function listarPorIdTarefa($id)
    {
        try{
            return $this->DAO->listarPorIdTarefa($id);
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

    }

    public function listarPorIdUsuario($id)
    {
        try{
            return $this->DAO->listarPorIdUsuario($id);
        }catch (PDOException $excecaoPDO){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

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