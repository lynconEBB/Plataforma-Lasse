<?php

class PdoFactory{
    const host = "localhost";
    const banco = "dblpm";
    const usuario = "root";
    const senha = "";
    const options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_CASE => PDO::CASE_NATURAL, PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING];

    public static function criarConexao(){
        try{
            $pdo = new PDO("mysql:host=".self::host.";dbname=".self::banco,self::usuario,self::senha,self::options);
            return $pdo;
        }catch (PDOException $error){
            return $error->getMessage();
        }
    }
}

