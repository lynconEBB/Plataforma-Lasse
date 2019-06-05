<?php

class ProjetoModel extends CrudModel {
    private $id;
    private $dataFinalizacao;
    private $dataInicio;
    private $totalGasto;
    private $descricao;
    private $nome;
    private $tarefas;

    public function __construct($dataFinalizacao, $dataInicio, $descricao, $nome,$id=null, $tarefas=null){
        $this->id = $id;
        $this->dataFinalizacao = $dataFinalizacao;
        $this->dataInicio = $dataInicio;
        $this->totalGasto = 0;
        $this->descricao = $descricao;
        $this->nome = $nome;
        $this->tarefas = $tarefas;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
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