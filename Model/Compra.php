<?php

class Compra{
    private $id;
    private $proprosito;
    private $totalGasto;
    private $itens;

    public function __construct($id, $proprosito, $totalGasto, $itens){
        $this->id = $id;
        $this->proprosito = $proprosito;
        $this->totalGasto = $totalGasto;
        $this->itens = $itens;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getProprosito(){
        return $this->proprosito;
    }

    public function setProprosito($proprosito){
        $this->proprosito = $proprosito;
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        $this->totalGasto = $totalGasto;
    }

    public function getItens(){
        return $this->itens;
    }

    public function setItens($itens){
        $this->itens = $itens;
    }



}