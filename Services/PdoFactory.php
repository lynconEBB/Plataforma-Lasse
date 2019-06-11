<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PdoFactory{
    const HOST = "localhost";
    const BANCO = "dbLPM";
    const USUARIO = "root";
    const SENHA = "bancodedados";
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