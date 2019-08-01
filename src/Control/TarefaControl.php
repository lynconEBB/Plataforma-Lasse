<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\TarefaDao;
use Lasse\LPM\Model\TarefaModel;
use PDOException;

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
        try{
            $tarefa = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],null,null,null,null,null);

            $projetoControl = new ProjetoControl();
            $projeto = $projetoControl->listarPorId($_POST['idProjeto']);
            if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                $this->DAO->cadastrar($tarefa,$_POST['idProjeto']);
            } else {
                throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
            }
        } catch (PDOException $pdoException){
            $_SESSION['danger'] = 'Erro durante cadastro no banco de dados';
        } catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        } finally {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    protected function excluir(int $id){
        try{
            $this -> DAO -> excluir($id);
            $projetoControl = new ProjetoControl();
            $projetoControl->atualizaTotal($_POST['idProjeto']);
        } catch (PDOException $pdoException){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
        } finally {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    public function listar() {
        try{
            return $this->DAO->listar();
        }catch (PDOException $exception){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /menu/tarefa');
            die();
        }
    }

    protected function atualizar(){
        try{
            $tarefa = new TarefaModel($_POST['nomeTarefa'],$_POST['descricao'],$_POST['estado'],$_POST['dtInicio'],$_POST['dtConclusao'],$_POST['id'],null,null,null,null);

            $projetoControl = new ProjetoControl();
            $projeto = $projetoControl->listarPorId($_POST['idProjeto']);

            if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                $this -> DAO ->atualizar($tarefa);
            } else {
                throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
            }
        } catch (PDOException $pdoException){
            $_SESSION['danger'] = 'Erro durante cadastro no banco de dados';
        } catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        } finally {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        }

    }

    public function listarPorIdProjeto($id){
        try{
            return $this->DAO->listarPorIdProjeto($id);
        }catch (PDOException $exception){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /erro');
            die();
        }
    }

    public function listarPorId($id){
        try{
            return $this->DAO->listarPorId($id);
        }catch (PDOException $exception){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /erro');
            die();
        }
    }


    public function descobrirIdProjeto($id){
        try{
            return $this->DAO->descobrirIdProjeto($id);
        }catch (PDOException $exception){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /erro');
            die();
        }

    }

    public function atualizaTotal($idTarefa){
        try{
            $tarefa = $this->listarPorId($idTarefa);
            $this->DAO->atualizaTotal($tarefa);
            $idProjeto = $this->DAO->descobrirIdProjeto($idTarefa);
            $projetoControl = new ProjetoControl();
            $projetoControl->atualizaTotal($idProjeto);
        }catch (PDOException $exception){
            $_SESSION['danger'] = 'Erro durante atualização no banco de dados.';
            header('Location: /erro');
            die();
        }
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
                    require '../View/telaTarefa.php';
                }
        }
    }
}
