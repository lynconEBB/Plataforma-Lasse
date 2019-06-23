<?php

class ItemModel
{
    private $id;
    private $valorParcial;
    private $valor;
    private $nome;
    private $quantidade;

    public function __construct($valor, $nome, $quantidade,$id=null){
        $this->id = $id;
        $this->valor = $valor;
        $this->nome = $nome;
        $this->quantidade = $quantidade;
        $this->valorParcial = $this->valor * $this->quantidade;
    }

    public function getId(){
        return $this->id;
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

    public function getValorParcial()
    {
        return $this->valorParcial;
    }

    public function setValorParcial($valorParcial)
    {
        $this->valorParcial = $valorParcial;
    }

}