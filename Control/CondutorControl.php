<?php

class CondutorControl extends CrudControl {

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new CondutorDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarCondutor':
                $this->cadastrar();
                header('Location: /menu/condutor');
                break;
            case 'excluirCondutor':
                $this->excluir($_POST['id']);
                header('Location: /menu/condutor');
                break;
            case 'alterarCondutor':
                $this->atualizar();
                header('Location: /menu/condutor');
                break;
        }
    }

    public function cadastrar(){
        $condutor = new CondutorModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH']);
        $this->DAO->cadastrar($condutor);
    }

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    public function listarPorId($id){
        return $this -> DAO -> listarPorId($id);
    }

    protected function atualizar(){
        $condutor = new CondutorModel($_POST['nomeCondutor'],$_POST['cnh'],$_POST['validadeCNH'],$_POST['id']);
        $this -> DAO -> atualizar($condutor);;
    }

    public function processaRequisicao(string $parametro)
    {
       switch ($parametro){
           case 'listaCondutores':
               $condutores = $this->listar();
               require '../View/CondutorView.php';
               break;
       }
    }
}
