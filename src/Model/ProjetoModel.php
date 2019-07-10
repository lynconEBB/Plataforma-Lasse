<?php

namespace Lasse\LPM\Model;

class ProjetoModel
{
    private $id;
    private $dataFinalizacao;
    private $dataInicio;
    private $totalGasto;
    private $descricao;
    private $nome;
    private $tarefas;
    private $participantes;

    public function __construct($dataFinalizacao, $dataInicio, $descricao, $nome,$id, $tarefas,$totalGasto,$participantes){
        $this->id = $id;
        $this->dataFinalizacao = $dataFinalizacao;
        $this->dataInicio = $dataInicio;
        $this->descricao = $descricao;
        $this->nome = $nome;
        $this->tarefas = $tarefas;
        $this->participantes = $participantes;
        if($tarefas != null && $totalGasto == null){
            $this->calculaTotal();
        }elseif($totalGasto == null){
            $this->totalGasto = 0;
        }else{
            $this->totalGasto = $totalGasto;
        }
    }

    public function calculaTotal()
    {
        $total = 0;
        foreach ($this->tarefas as $tarefa){
            $total += $tarefa->getTotalGasto();
        }
        $this->totalGasto = $total;
    }

    public function getParticipantes()
    {
        return $this->participantes;
    }

    public function setParticipantes($participantes): void
    {
        $this->participantes = $participantes;
    }


    public function getId(){
        return $this->id;
    }

    public function getDataFinalizacao(){
        return $this->dataFinalizacao;
    }

    public function setDataFinalizacao($dataFinalizacao){
        $this->dataFinalizacao = $dataFinalizacao;
    }

    public function getDataInicio(){
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio){
        $this->dataInicio = $dataInicio;
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        $this->totalGasto = $totalGasto;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getTarefas(){
        return $this->tarefas;
    }

    public function setTarefas($tarefas){
        $this->tarefas = $tarefas;
    }
}

?>