<?php

class VeiculoModel
{
    private $id;
    private $nome;
    private $tipo;
    private $dataRetirada;
    private $dataDevolucao;
    private $horarioRetirada;
    private $horarioDevolucao;
    private $condutor;

    public function __construct($nome, $tipo, $dataRetirada, $dataDevolucao, $horarioRetirada, $horarioDevolucao, $condutor,$id=null){
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->dataRetirada = $dataRetirada;
        $this->dataDevolucao = $dataDevolucao;
        $this->horarioRetirada = $horarioRetirada;
        $this->horarioDevolucao = $horarioDevolucao;
        $this->condutor = $condutor;
        $this->id=$id;
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

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }

    public function getDataRetirada(){
        return $this->dataRetirada;
    }

    public function setDataRetirada($dataRetirada){
        $this->dataRetirada = $dataRetirada;
    }

    public function getDataDevolucao(){
        return $this->dataDevolucao;
    }

    public function setDataDevolucao($dataDevolucao){
        $this->dataDevolucao = $dataDevolucao;
    }

    public function getHorarioRetirada(){
        return $this->horarioRetirada;
    }

    public function setHorarioRetirada($horarioRetirada){
        $this->horarioRetirada = $horarioRetirada;
    }

    public function getHorarioDevolucao(){
        return $this->horarioDevolucao;
    }

    public function setHorarioDevolucao($horarioDevolucao){
        $this->horarioDevolucao = $horarioDevolucao;
    }

    public function getCondutor():CondutorModel{
        return $this->condutor;
    }

    public function setCondutor($condutor){
        $this->condutor = $condutor;
    }




}