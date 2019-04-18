<?php
require_once '../Services/PdoFactory.php';

abstract class CrudDAO{
    public $pdo;

    public function __construct(){
        $this->pdo = PdoFactory::criarConexao();
    }

}