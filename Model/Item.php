<?php

class Item{
    private $id;
    private $valor;
    private $nome;
    private $quantidade;

    public function __construct($id, $valor, $nome, $quantidade){
        $this->id = $id;
        $this->valor = $valor;
        $this->nome = $nome;
        $this->quantidade = $quantidade;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getValor(){
        return $this->valor;
    }

    public function setValor($valor){
        $this->valor = $valor;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getQuantidade(){
        return $this->quantidade;
    }

    public function setQuantidade($quantidade){
        $this->quantidade = $quantidade;
    }

}