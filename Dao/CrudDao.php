<?php

abstract class CrudDao{
    public $pdo;

    public function __construct(){
        $this->pdo = PdoFactory::criarConexao();
    }

    public abstract function excluir($id);
    public abstract function listar();

}