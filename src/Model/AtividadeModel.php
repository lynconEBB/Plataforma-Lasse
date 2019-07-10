<?php

namespace Lasse\LPM\Model;

class AtividadeModel {
    private $id;
    private $tipo;
    private $tempoGasto;
    private $comentario;
    private $dataRealizacao;
    private $totalGasto;
    private $usuario;

    public function __construct(string $tipo, string $tempoGasto, string $comentario, string $dataRealizacao, UsuarioModel $usuario,$id = null){
        $this->tipo = $tipo;
        $this->tempoGasto = $tempoGasto;
        $this->comentario = $comentario;
        $this->dataRealizacao = $dataRealizacao;
        $this->usuario = $usuario;
        $this->totalGasto =  $this->usuario->getValorHora() * $this->tempoGasto;
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }

    public function getTempoGasto(){
        return $this->tempoGasto;
    }

    public function setTempoGasto($tempoGasto){
        $this->tempoGasto = $tempoGasto;
    }

    public function getComentario(){
        return $this->comentario;
    }

    public function setComentario($comentario){
        $this->comentario = $comentario;
    }

    public function getDataRealizacao(){
        return $this->dataRealizacao;
    }

    public function setDataRealizacao($dataRealizacao){
        $this->dataRealizacao = $dataRealizacao;
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        $this->totalGasto = $totalGasto;
    }

    public function getUsuario(){
        return $this->usuario;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

}