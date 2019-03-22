<?php

class Conexao{
    public static function conectar(){
        try{
            $pdo = new PDO("mysql:host=localhost;dbname=dbLPM","root","bancodedados");
        }catch (PDOException $e){
            echo $e->getMessage();
        }
        return $pdo;
    }
}
