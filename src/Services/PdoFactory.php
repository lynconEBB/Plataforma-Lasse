<?php

namespace Lasse\LPM\Services;

use PDO;
use PDOException;

class PdoFactory{
    const HOST = "localhost";
    const BANCO = "dbLPM";
    const USUARIO = "lyncon";
    const SENHA = "bancodedados";
    const OPCOES = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_CASE => PDO::CASE_NATURAL, PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING];

    public static function criarConexao(){
        $pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::BANCO.";charset=utf8",self::USUARIO,self::SENHA,self::OPCOES);
        return $pdo;

    }
}
