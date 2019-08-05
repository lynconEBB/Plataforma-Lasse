<?php

namespace Lasse\LPM\Model;

use DateTime;
use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class ViagemModel
{
    private $id;
    private $viajante;
    private $veiculo;
    private $origem;
    private $destino;
    private $dtIda;
    private $dtVolta;
    private $passagem;
    private $justificativa;
    private $observacoes;
    private $EntradaHosp;
    private $SaidaHosp;
    private $gastos;
    private $totalGasto;

    public function __construct($viajante, $veiculo, $origem, $destino, $dtIda, $dtVolta, $passagem, $justificativa, $observacoes, $EntradaHosp, $SaidaHosp, $totalGasto,$id,$gastos){
        /*$this->setViajante($viajante);
        $this->setVeiculo($veiculo);
        $this->setOrigem($origem);
        $this->setDestino($destino);
        $this->setDtIda($dtIda);
        $this->setDtVolta($dtVolta);
        $this->setPassagem($passagem);
        $this->setJustificativa($justificativa);
        $this->setObservacoes($observacoes);*/
        $this->setEntradaHosp($EntradaHosp);
        /*$this->setSaidaHosp($SaidaHosp);
        $this->setGastos($gastos);
        $this->setTotalGasto($totalGasto);
        $this->setId($id);*/
    }

    public function calculaTotal()
    {
        $total =0;
        foreach ($this->gastos as $gasto){
            $total += $gasto->getValor();
        }
        $this->totalGasto = $total;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getPassagem(){
        return $this->passagem;
    }

    public function setPassagem($passagem): void
    {
        Validacao::validar('Passagem',$passagem,'obrigatorio','texto');
        $this->passagem = $passagem;
    }

    public function getEntradaHosp():DateTime{
        return $this->EntradaHosp;
    }

    public function setEntradaHosp($EntradaHosp): void
    {
        Validacao::validar('Entrada na Hospedagem',$EntradaHosp,'dataHora');
        $this->EntradaHosp = Formatacao::formataDataHora($EntradaHosp);
    }

    public function getSaidaHosp():DateTime
    {
        return $this->SaidaHosp;
    }

    public function setSaidaHosp($SaidaHosp): void{
        $this->SaidaHosp = $SaidaHosp;
    }

    public function getGastos(){
        return $this->gastos;
    }

    public function setGastos($gastos){
        $this->gastos = $gastos;
        $this->calculaTotal();
    }

    public function getTotalGasto(): float{
        return $this->totalGasto;
    }

    public function setTotalGasto(float $totalGasto){
        if($gastos == null){
            $this->totalGasto = 0.00;
        }else{
            $this->calculaTotal();
        }
        $this->totalGasto = $totalGasto;
    }


    public function getViajante(){
        return $this->viajante;
    }

    public function setViajante($viajante){
        $this->viajante = $viajante;
    }

    public function getVeiculo(){
        return $this->veiculo;
    }

    public function setVeiculo($veiculo){
        $this->veiculo = $veiculo;
    }

    public function getOrigem(){
        return $this->origem;
    }

    public function setOrigem($origem){
        $this->origem = $origem;
    }

    public function getDestino(){
        return $this->destino;
    }

    public function setDestino($destino){
        $this->destino = $destino;
    }

    public function getDtIda(){
        return $this->dtIda;
    }

    public function setDtIda($dtIda){
        $this->dtIda = $dtIda;
    }

    public function getDtVolta(){
        return $this->dtVolta;
    }

    public function setDtVolta($dtVolta){
        $this->dtVolta = $dtVolta;
    }

    public function getJustificativa(){
        return $this->justificativa;
    }

    public function setJustificativa($justificativa){
        $this->justificativa = $justificativa;
    }

    public function getObservacoes(){
        return $this->observacoes;
    }

    public function setObservacoes($observacoes){
        $this->observacoes = $observacoes;
    }
}