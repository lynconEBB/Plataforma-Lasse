<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ProjetoDao;
use Lasse\LPM\Model\ProjetoModel;
use PDOException;

class ProjetoControl extends CrudControl
{

    public function __construct()
    {
        UsuarioControl::verificar();
        $this->DAO = new ProjetoDao();
        parent::__construct();
    }

    public function defineAcao($acao)
    {
        switch ($acao){
            case 'cadastrarProjeto':
                $this->cadastrar();
                header('Location: /menu/projeto');
                die();
                break;
            case 'excluirProjeto':
                $this->excluir($_POST['id']);
                header('Location: /menu/projeto');
                die();
                break;
            case 'alterarProjeto':
                $this->atualizar();
                header('Location: /menu/projeto');
                die();
                break;
            case 'adicionarFuncionario':
                $this->addFuncionario();
                header('Location: /menu/projeto');
                die();
                break;
        }
    }

    protected function cadastrar()
    {
        try{
            $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],null,null,null,null);
            $this->DAO->cadastrar($projeto);
        }catch (PDOException $pdoexcecao){
            $_SESSION['danger'] = 'Erro durante cadastro no banco de dados';
        }catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        }
    }

    protected function excluir(int $id)
    {
        try{
            $this->DAO->excluir($id);
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
        }

    }

    public function listar()
    {
        try{
            return $this->DAO->listar();
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    protected function atualizar(){
        try{
            $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id'],null,null,null);
            $this -> DAO -> alterar($projeto);
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro durante atualização no banco de dados';

        }catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        }
    }

    public function listarPorIdUsuario($id)
    {
        try{
            return $this->DAO->listarPorIdUsuario($id);
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    public function listarPorId($id)
    {
        try{
            return $this->DAO->listarPorId($id);
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    public function procuraFuncionario($idProjeto,$idUsuario)
    {
        try{
            $result = $this->DAO->procuraFuncionario($idProjeto,$idUsuario);
            if ($result > 0) {
                return true;
            }else {
                return false;
            }
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro com banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    public function atualizaTotal($idProjeto)
    {
        try{
            $projeto = $this->DAO->listarPorId($idProjeto);
            $this->DAO->atualizarTotal($projeto);
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro com banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    public function verificaDono($idProjeto)
    {
        try{
            $numRows = $this->DAO->verificaDono($idProjeto);
            if($numRows > 0 ){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro com banco de dados';
            header("Location: /menu/projeto");
            die();
        }
    }

    public function addFuncionario()
    {
        try{
            if(!$this->procuraFuncionario($_POST['idProjeto'],$_POST['idUsuario'])){
                $this->DAO->adicionarFuncionario($_POST['idUsuario'],$_POST['idProjeto']);
            }else{
                throw new Exception('Funcionário já inserido');
            }
        }catch (PDOException $pdoexcecao) {
            $_SESSION['danger'] = 'Erro com banco de dados';
        }catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        }
    }

    public function processaRequisicao(string $parametro)
    {
       switch ($parametro){
           case 'listaProjetos':
               $usuarioControl = new UsuarioControl();
               $usuarios = $usuarioControl->listar();
               $projetos = $this->listarPorIdUsuario($_SESSION['usuario-id']);
               require '../View/telaProjetos.php';
       }
    }
}