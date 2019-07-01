<?php

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
                break;
            case 'excluirProjeto':
                $this->excluir($_POST['id']);
                header('Location: /menu/projeto');
                break;
            case 'alterarProjeto':
                $this->atualizar();
                header('Location: /menu/projeto');
                break;
            case 'adicionarFuncionario':
                $this->addFuncionario();
                header('Location: /menu/projeto');
                break;
        }
    }

    protected function cadastrar()
    {
        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],null,null,null,null);
        $this->DAO->cadastrar($projeto);
    }

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);
    }

    public function listar() {
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id'],null,null,null);
        $this -> DAO -> alterar($projeto);
    }

    public function listarPorIdUsuario($id)
    {
        return $this->DAO->listarPorIdUsuario($id);
    }

    public function procuraFuncionario($idProjeto,$idUsuario)
    {
        return $this->DAO->procuraFuncionario($idProjeto,$idUsuario);
    }

    public function atualizaTotal($idProjeto)
    {
        $projeto = $this->DAO->listarPorId($idProjeto);
        $this->DAO->atualizarTotal($projeto);
    }

    public function verificaDono($idProjeto)
    {
        $numRows = $this->DAO->verificaDono($idProjeto);
        if($numRows > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function addFuncionario()
    {
        if($this->procuraFuncionario($_POST['idProjeto'],$_POST['idUsuario']) == 0 ){
            $this->DAO->adicionarFuncionario($_POST['idUsuario'],$_POST['idProjeto']);
        }else{
            $_SESSION['danger'] = 'Usuario ja Inserido no Projeto :(';
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