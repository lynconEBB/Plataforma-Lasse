<?php

namespace Lasse\LPM\Model;

use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class CompraModel
{
    private $id;
    private $proposito;
    private $totalGasto = 0;
    private $naturezaOrcamentaria;
    private $fonteRecurso;
    private $itens;
    private $comprador;

    public function __construct($proposito,$totalGasto, $itens, $id,$comprador,$fonteRecurso,$naturezaOrcamentaria)
    {
        $this->setId($id);
        $this->setProposito($proposito);
        $this->setItens($itens);
        $this->setComprador($comprador);
        $this->setFonteRecurso($fonteRecurso);
        $this->setNaturezaOrcamentaria($naturezaOrcamentaria);
        $this->setTotalGasto($totalGasto);
    }

    public function toArray()
    {
        if (is_array($this->itens)) {
            $itens = array();
            foreach ($this->itens as $item) {
                $itens[] = $item->toArray();
            }
        } else {
            $itens = null;
        }
        $array = [
            "id" => $this->id,
            "proposito" => $this->proposito,
            "naturezaOrcamentaria" => $this->naturezaOrcamentaria,
            "fonte" => $this->fonteRecurso,
            "totalGasto" => $this->totalGasto,
            "itens" => $itens,
            "comprador" => $this->getComprador()->toArray()
        ];
        return $array;
    }

    public function calculaTotal(){
        $total = 0;
        if (is_array($this->itens)) {
            foreach ($this->itens as $item){
                $total += $item->getValorParcial();
            }
        }
        $this->totalGasto = number_format($total,2,".","");
    }

    public function getNaturezaOrcamentaria()
    {
        return $this->naturezaOrcamentaria;
    }

    public function setNaturezaOrcamentaria($naturezaOrcamentaria)
    {
        Validacao::validar("Natureza Orçamentária",$naturezaOrcamentaria,"texto","obrigatorio");
        $this->naturezaOrcamentaria = $naturezaOrcamentaria;
    }

    public function getFonteRecurso()
    {
        return $this->fonteRecurso;
    }

    public function setFonteRecurso($fonteRecurso)
    {
        Validacao::validar("Fonte de Recurso",$fonteRecurso,"texto","obrigatorio");
        $this->fonteRecurso = $fonteRecurso;
    }

    public function getComprador(): UsuarioModel
    {
        return $this->comprador;
    }

    public function setComprador($comprador): void
    {
        $this->comprador = $comprador;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getProposito(){
        return $this->proposito;
    }

    public function setProposito($proposito)
    {
        Validacao::validar('Proprósito',$proposito,'obrigatorio','texto');
        $this->proposito = $proposito;
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        if(is_null($totalGasto)){
            $this->calculaTotal();
        }else{
            Validacao::validar('Total Gasto',$totalGasto,'monetario');
            $this->totalGasto = Formatacao::formataMonetario($totalGasto);
        }
    }

    public function getItens(){
        return $this->itens;
    }

    public function setItens($itens)
    {
        $this->itens = $itens;
    }



}
