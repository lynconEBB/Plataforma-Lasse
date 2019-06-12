<?php

class CompraModel extends CrudModel{
    private $id;
    private $proposito;
    private $totalGasto;
    private $itens;

    public function __construct($proposito,$totalGasto=null, $itens=null,$id=null){
        $this->id = $id;
        $this->proposito = $proposito;
        $this->totalGasto = $totalGasto;
        $this->itens = $itens;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getProposito(){
        return $this->proposito;
    }

    public function setProposito($proposito){
        $this->proposito = $proposito;
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