<?php

class CompraModel
{
    private $id;
    private $proposito;
    private $totalGasto = 0;
    private $itens;
    private $comprador;

    public function __construct($proposito,$totalGasto,  $itens , $id,$comprador)
    {
        $this->id = $id;
        $this->proposito = $proposito;
        $this->itens = $itens;
        $this->comprador = $comprador;
        if($this->itens != null and $totalGasto == null){
           $this->calculaTotal();
        }else{
            $this->totalGasto = $totalGasto;
        }
    }

    public function calculaTotal(){
        $total = 0;
        foreach ($this->itens as $item){
            $total += $item->getValorParcial();
        }
        $this->totalGasto = $total;
    }

    public function getComprador()
    {
        return $this->comprador;
    }

    public function setComprador($comprador): void
    {
        $this->comprador = $comprador;
    }

    public function getId(){
        return $this->id;
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
        $this->calculaTotal();
    }



}