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

    abstract protected function defineAcao($acao);

    abstract protected function cadastrar();

    abstract protected function excluir($id);

    abstract protected function listar();

    abstract protected function atualizar();

}