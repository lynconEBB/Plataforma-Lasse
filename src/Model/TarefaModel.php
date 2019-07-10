<?php

namespace Lasse\LPM\Model;

class TarefaModel
{
    private $id;
    private $nome;
    private $descricao;
    private $estado;
    private $dataInicio;
    private $dataConclusao;
    private $totalGasto;
    private $atividades;
    private $viagens;
    private $compras;

    public function __construct($nome, $descricao, $estado, $dataInicio, $dataConclusao,$id,$atividades, $viagens, $compras,$totalGasto){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->estado = $estado;
        $this->dataInicio = $dataInicio;
        $this->dataConclusao = $dataConclusao;
        $this->atividades = $atividades;
        $this->viagens = $viagens;
        $this->compras = $compras;
        if(($atividades != null || $viagens != null || $compras != null) && $totalGasto == null){
            $this->calculaTotal();
        }elseif($totalGasto == null) {
            $this->totalGasto = 0;
        }else{
            $this->totalGasto = $totalGasto;
        }
        $this->id = $id;
    }

    public function calculaTotal()
    {
        $total = 0;
        if($this->atividades != null){
            foreach ($this->atividades as $atividade){
                $total += $atividade->getTotalGasto();
            }
        }
        if($this->compras != null){
            foreach ($this->compras as $compra){
                $total += $compra->getTotalGasto();
            }
        }
        if($this->viagens != null){
            foreach ($this->viagens as $viagem){
                $total += $viagem->getTotalGasto();
            }
        }
        $this->totalGasto = $total;
    }

    public function getTotalGasto()
    {
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto): void
    {
        $this->totalGasto = $totalGasto;
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function getDataInicio(){
        return $this->dataInicio;
    }

    public function setDataInicio($dataIncio){
        $this->dataIncio = $dataIncio;
    }

    public function getDataConclusao(){
        return $this->dataConclusao;
    }

    public function setDataConclusao($dataConclusao){
        $this->dataConclusao = $dataConclusao;
    }

    public function getAtividades(){
        return $this->atividades;
    }

    public function setAtividades($atividades){
        $this->atividades = $atividades;
    }

    public function getViagens(){
        return $this->viagens;
    }

    public function setViagens($viagens){
        $this->viagens = $viagens;
    }

    public function getCompras(){
        return $this->compras;
    }

    public function setCompras($compras){
        $this->compras = $compras;
    }




}