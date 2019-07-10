<?php

namespace Lasse\LPM\Control;

abstract class CrudControl{
    public $DAO;

    public function __construct(){
        if( isset($_POST['acao']) ){
            $acao = $_POST['acao'];
            $this->defineAcao($acao);
        }
    }

    abstract public function defineAcao($acao);

    abstract public function processaRequisicao(string $parametro);

    abstract protected function cadastrar();

    abstract protected function excluir(int $id);

    abstract protected function listar();

    abstract protected function atualizar();

}