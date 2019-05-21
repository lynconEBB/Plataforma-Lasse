<?php

class PdoFactory{
    const HOST = "localhost";
    const BANCO = "dblpm";
    const USUARIO = "root";
    const SENHA = "";
    const OPCOES = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_CASE => PDO::CASE_NATURAL, PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING];

    public static function criarConexao(){
        try{
            $pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::BANCO,self::USUARIO,self::SENHA,self::OPCOES);
            return $pdo;
        }catch (PDOException $error){
            return $error->getMessage();
        }
    }
}

