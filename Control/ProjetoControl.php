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
                break;
            case 'excluirProjeto':
                $this->excluir($_POST['id']);
                break;
            case 'alterarProjeto':
                $this->atualizar();
                break;
        }
    }

    protected function cadastrar()
    {
        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto']);
        $this->DAO->cadastrar($projeto);

        header('Location: /menu/projeto');
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);

        header('Location: /menu/projeto');
    }

    public function listar() {
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $projeto = new ProjetoModel($_POST['dataFinalizacao'],$_POST['dataInicio'],$_POST['descricao'],$_POST['nomeProjeto'],$_POST['id']);
        $this -> DAO -> alterar($projeto);

        header('Location: /menu/projeto');
    }

    public function listarPorIdUsuario($id)
    {
        return $this->DAO->listarPorIdUsuario($id);
    }

    public function procuraFuncionario($id)
    {
        return $this->DAO->procuraFuncionario($id);
    }

    public function processaRequisicao(string $parametro)
    {
       switch ($parametro){
           case 'listaProjetos':
               $projetos = $this->listarPorIdUsuario($_SESSION['usuario-id']);
               require '../View/ProjetoView.php';
       }
    }
}