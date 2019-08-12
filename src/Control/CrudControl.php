<?php

namespace Lasse\LPM\Control;

abstract class CrudControl{
    public $DAO;


    abstract public function defineAcao($acao);

    abstract public function processaRequisicao();

    abstract protected function cadastrar($info);

    abstract protected function excluir(int $id);

    abstract protected function listar();

    abstract protected function atualizar();

}