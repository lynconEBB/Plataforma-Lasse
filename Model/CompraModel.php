<?php

class CompraModel extends CrudModel
{
    private $id;
    private $proposito;
    private $totalGasto = 0;
    private $itens;

    public function __construct(string $proposito,$totalGasto = null, array $itens = null, int $id = null)
    {
        $this->id = $id;
        $this->proposito = $proposito;
        $this->itens = $itens;
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

    public function setId(int $id)
    {
        $this->id = $id;
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