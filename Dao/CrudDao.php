<?php
require_once '../Services/Autoload.php';

abstract class CrudDao{
    public $pdo;

    public function __construct(){
        $this->pdo = PdoFactory::criarConexao();
    }

    abstract function excluir($id);
    abstract function listar();

}