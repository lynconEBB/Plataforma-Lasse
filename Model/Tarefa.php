<?php
class Tarefa{
    private $id;
    private $nome;
    private $descricao;
    private $estado;
    private $dataInicio;
    private $dataConclusao;
    private $atividades;
    private $viagens;
    private $compras;

    public function __construct($nome, $descricao, $estado, $dataInicio, $dataConclusao,$id=null,$atividades=null, $viagens=null, $compras=null){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->estado = $estado;
        $this->dataInicio = $dataInicio;
        $this->dataConclusao = $dataConclusao;
        $this->atividades = $atividades;
        $this->viagens = $viagens;
        $this->compras = $compras;
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id): void{
        $this->id = $id;
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