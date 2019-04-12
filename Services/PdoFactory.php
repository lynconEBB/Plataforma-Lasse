<?php

class PdoFactory{
    const host = "localhost";
    const banco = "dbLPM";
    const usuario = "root";
    const senha = "";

    public static function criarConexao(){
        try{
            $pdo = new PDO("mysql:host=".self::host.";dbname=".self::banco,self::usuario,self::senha);
            return $pdo;
        }catch (PDOException $error){
            return $error->getMessage();
        }
    }
}

