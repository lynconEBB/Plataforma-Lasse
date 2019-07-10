<?php

namespace Lasse\LPM\Model;

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
    private $dtEntradaHosp;
    private $dtSaidaHosp;
    private $horaEntradaHosp;
    private $horaSaidaHosp;
    private $gastos;
    private $totalGasto;

    public function __construct($viajante, $veiculo, $origem, $destino, $dtIda, $dtVolta, $passagem, $justificativa, $observacoes, $dtEntradaHosp, $dtSaidaHosp, $horaEntradaHosp, $horaSaidaHosp, $id=null,$gastos=null){
        $this->viajante = $viajante;
        $this->veiculo = $veiculo;
        $this->origem = $origem;
        $this->destino = $destino;
        $this->dtIda = $dtIda;
        $this->dtVolta = $dtVolta;
        $this->passagem = $passagem;
        $this->justificativa = $justificativa;
        $this->observacoes = $observacoes;
        $this->dtEntradaHosp = $dtEntradaHosp;
        $this->dtSaidaHosp = $dtSaidaHosp;
        $this->horaEntradaHosp = $horaEntradaHosp;
        $this->horaSaidaHosp = $horaSaidaHosp;
        $this->gastos = $gastos;
        if($gastos == null){
            $this->totalGasto = 0.00;
        }else{
            $this->calculaTotal();
        }
        $this->id=$id;
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

    public function getPassagem(){
        return $this->passagem;
    }

    public function setPassagem($passagem): void{
        $this->passagem = $passagem;
    }

    public function getDtEntradaHosp(){
        return $this->dtEntradaHosp;
    }

    public function setDtEntradaHosp($dtEntradaHosp): void{
        $this->dtEntradaHosp = $dtEntradaHosp;
    }

    public function getDtSaidaHosp(){
        return $this->dtSaidaHosp;
    }

    public function setDtSaidaHosp($dtSaidaHosp): void{
        $this->dtSaidaHosp = $dtSaidaHosp;
    }

    public function getHoraEntradaHosp(){
        return $this->horaEntradaHosp;
    }

    public function setHoraEntradaHosp($horaEntradaHosp): void{
        $this->horaEntradaHosp = $horaEntradaHosp;
    }

    public function getHoraSaidaHosp(){
        return $this->horaSaidaHosp;
    }

    public function setHoraSaidaHosp($horaSaidaHosp): void{
        $this->horaSaidaHosp = $horaSaidaHosp;
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