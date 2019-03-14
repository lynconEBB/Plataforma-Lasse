<?php

class Condutor{
    private $nome;
    private $cnh;
    private $validadeCNH;

    public function __construct($nome, $cnh, $validadeCNH){
        $this->nome = $nome;
        $this->cnh = $cnh;
        $this->validadeCNH = $validadeCNH;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getCnh(){
        return $this->cnh;
    }

    public function setCnh($cnh){
        $this->cnh = $cnh;
    }

    public function getValidadeCNH(){
        return $this->validadeCNH;
    }

    public function setValidadeCNH($validadeCNH){
        $this->validadeCNH = $validadeCNH;
    }




}