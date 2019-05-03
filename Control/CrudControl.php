<?php

abstract class CrudControl{
    public $DAO;

    public function __construct(){
        if( isset($_POST['acao']) ){
            $acao = $_POST['acao'];
            $this->defineAcao($acao);
        }elseif (isset($_GET['acao'])){
            $acao = $_GET['acao'];
            $this->defineAcao($acao);
        }
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
                $this->cadastrar();
                break;
            case 2:
                $this->excluir($_GET['id']);
                break;
            case 3:
                $this->atualizar();
                break;
        }
    }

    abstract protected function cadastrar();

    abstract protected function excluir($id);

    abstract protected function listar();

    abstract protected function atualizar();

}