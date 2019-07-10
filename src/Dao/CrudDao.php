<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Services\PdoFactory;

abstract class CrudDao{
    public $pdo;

    public function __construct(){
        $this->pdo = PdoFactory::criarConexao();
    }

    public abstract function excluir($id);
    public abstract function listar();

}