<?php
class Tarefa{
    private $nome;
    private $descricao;
    private $estado;
    private $dataIncio;
    private $dataConclusao;
    private $atividades;
    private $viagens;
    private $compras;

    public function __construct($nome, $descricao, $estado, $dataIncio, $dataConclusao, $atividades, $viagens, $compras){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->estado = $estado;
        $this->dataIncio = $dataIncio;
        $this->dataConclusao = $dataConclusao;
        $this->atividades = $atividades;
        $this->viagens = $viagens;
        $this->compras = $compras;
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

    public function getDataIncio(){
        return $this->dataIncio;
    }

    public function setDataIncio($dataIncio){
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