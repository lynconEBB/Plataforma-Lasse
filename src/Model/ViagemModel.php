<?php

namespace Lasse\LPM\Model;

use DateTime;
use Exception;
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
        $this->setViajante($viajante);
        $this->setVeiculo($veiculo);
        $this->setOrigem($origem);
        $this->setDestino($destino);
        $this->setDtIda($dtIda);
        $this->setDtVolta($dtVolta);
        $this->setPassagem($passagem);
        $this->setJustificativa($justificativa);
        $this->setObservacoes($observacoes);
        $this->setEntradaHosp($EntradaHosp);
        $this->setSaidaHosp($SaidaHosp);
        $this->setGastos($gastos);
        $this->setTotalGasto($totalGasto);
        $this->setId($id);
    }

    public function toArray()
    {
        if (is_array($this->gastos)) {
            $gastos = array();
            foreach ($this->gastos as $gasto) {
                $gastos[] = $gasto->toArray();
            }
        } else {
            $gastos = null;
        }
        $array = [
            "id" => $this->id,
            "origem" => $this->origem,
            "destino" => $this->destino,
            "dtIda" => $this->dtIda->format('d/m/Y'),
            "dtVolta" => $this->dtVolta->format('d/m/Y'),
            "passagem" => $this->passagem,
            "justificativa" => $this->justificativa,
            "obeservacoes" => $this->observacoes,
            "saidaHosp" => $this->SaidaHosp->format('d/m/Y h:i'),
            "entradaHosp" => $this->EntradaHosp->format('d/m/Y h:i'),
            "totalGasto" => $this->totalGasto,
            "gastos" => $gastos,
            "viajante" => $this->viajante->toArray(),
            "veiculo" => $this->veiculo->toArray()
        ];
        return $array;
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

    public function getEntradaHosp():DateTime
    {
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

    public function setSaidaHosp($SaidaHosp): void
    {
        Validacao::validar("Saida da Hospedagem",$SaidaHosp,'dataHora');
        $saidaFormat = Formatacao::formataDataHora($SaidaHosp);
        if ($saidaFormat > $this->EntradaHosp) {
            $this->SaidaHosp = $saidaFormat;
        } else {
            throw new Exception("A data e Hora de saida necessitam ser posteriores a entrada");
        }
    }

    public function getGastos(){
        return $this->gastos;
    }

    public function setGastos($gastos){
        $this->gastos = $gastos;
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        if(is_null($this->gastos) && is_null($totalGasto)){
            $this->totalGasto = 0.00;
        }elseif (is_array($this->gastos) && is_null($totalGasto)){
            $total = 0;
            foreach ($this->gastos as $gasto){
                $total += $gasto->getValor();
            }
            $this->totalGasto = $total;
        } else {
            Validacao::validar("Total Gasto",$totalGasto,'monetario');
            $this->totalGasto = Formatacao::formataMonetario($totalGasto);
        }
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
        Validacao::validar("Origem",$origem,'obrigatorio','texto');
        $this->origem = $origem;
    }

    public function getDestino(){
        return $this->destino;
    }

    public function setDestino($destino){
        Validacao::validar("Destino",$destino,'obrigatorio','texto');
        $this->destino = $destino;
    }

    public function getDtIda():DateTime{
        return $this->dtIda;
    }

    public function setDtIda($dtIda){
        Validacao::validar("Data Ida",$dtIda,'data');
        $this->dtIda = Formatacao::formataData($dtIda);
    }

    public function getDtVolta(){
        return $this->dtVolta;
    }

    public function setDtVolta($dtVolta){
        Validacao::validar("Data Volta",$dtVolta,'data');
        $voltaFormatada = Formatacao::formataData($dtVolta);
        if ($voltaFormatada > $this->dtIda) {
            $this->dtVolta = $voltaFormatada;
        } else {
            throw new Exception("A data de Volta deve ser posterior a data de Ida");
        }
    }

    public function getJustificativa()
    {
        return $this->justificativa;
    }

    public function setJustificativa($justificativa){
        Validacao::validar("Justificativa",$justificativa,'obrigatorio','texto');
        $this->justificativa = $justificativa;
    }

    public function getObservacoes(){
        return $this->observacoes;
    }

    public function setObservacoes($observacoes){
        Validacao::validar("Observações",$observacoes,'obrigatorio','texto');
        $this->observacoes = $observacoes;
    }
}