<?php

class Viagem{
    private $viajante;
    private $veiculo;
    private $atividade;
    private $meta;
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


    public function __construct($viajante, $veiculo, $atividade, $meta, $origem, $destino, $dtIda, $dtVolta, $passagem, $justificativa, $observacoes, $dtEntradaHosp, $dtSaidaHosp, $horaEntradaHosp, $horaSaidaHosp, $gastos, $totalGasto){
        $this->viajante = $viajante;
        $this->veiculo = $veiculo;
        $this->atividade = $atividade;
        $this->meta = $meta;
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

    public function getNomeConvenio(){
        return $this->nomeConvenio;
    }

    public function setNomeConvenio($nomeConvenio){
        $this->nomeConvenio = $nomeConvenio;
    }

    public function getNumeroConvenio(){
        return $this->numeroConvenio;
    }

    public function setNumeroConvenio($numeroConvenio){
        $this->numeroConvenio = $numeroConvenio;
    }

    public function getNumeroCentro(){
        return $this->numeroCentro;
    }

    public function setNumeroCentro($numeroCentro){
        $this->numeroCentro = $numeroCentro;
    }

    public function getAtividade(){
        return $this->atividade;
    }

    public function setAtividade($atividade){
        $this->atividade = $atividade;
    }

    public function getFonteRecurso(){
        return $this->fonteRecurso;
    }

    public function setFonteRecurso($fonteRecurso){
        $this->fonteRecurso = $fonteRecurso;
    }

    public function getMeta(){
        return $this->meta;
    }

    public function setMeta($meta){
        $this->meta = $meta;
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