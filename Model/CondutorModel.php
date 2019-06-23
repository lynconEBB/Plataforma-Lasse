<?php

class CondutorModel
{
    private $id;
    private $nome;
    private $cnh;
    private $validadeCNH;

    public function __construct($nome, $cnh, $validadeCNH,$id=null){
        $this->nome = $nome;
        $this->cnh = $cnh;
        $this->validadeCNH = $validadeCNH;
        $this->id = $id;
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
        $val = $this->validadeCNH;
        return $val;
    }

    public function setValidadeCNH($validadeCNH){
        $this->validadeCNH = $validadeCNH;
    }

    public function getId(){
        return $this->id;
    }
}