<?php

namespace Lasse\LPM\Model;

use DateTime;
use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class AtividadeModel {
    private $id;
    private $tipo;
    private $tempoGasto;
    private $comentario;
    private $dataRealizacao;
    private $totalGasto;
    private $usuario;

    public function __construct(string $tipo, string $tempoGasto, string $comentario, string $dataRealizacao, UsuarioModel $usuario,$id,$totalGasto){
        $this->setTipo($tipo);
        $this->setTempoGasto($tempoGasto);
        $this->setComentario($comentario);
        $this->setDataRealizacao($dataRealizacao);
        $this->setUsuario($usuario);
        $this->setTotalGasto($totalGasto);
        $this->setId($id);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->id,
            "tipo" => $this->tipo,
            "tempoGasto" => $this->tempoGasto,
            "comentario" => $this->comentario,
            "usuario" => $this->usuario->toArray(),
            "dataRealizacao" => $this->dataRealizacao,
            "totalGasto" => $this->totalGasto
        ];
        return $array;
    }

    public function getId(){
        return $this->id;
    }

    private function setId($id){
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        Validacao::validar('Tipo',$tipo,'obrigatorio','texto');
        $this->tipo = $tipo;
    }

    public function getTempoGasto(){
        return $this->tempoGasto;
    }

    public function setTempoGasto($tempoGasto){
        Validacao::validar('Tempo Gasto',$tempoGasto,'monetario');
        $this->tempoGasto = Formatacao::formataMonetario($tempoGasto);
    }

    public function getComentario(){
        return $this->comentario;
    }

    public function setComentario($comentario){
        Validacao::validar('Comentário',$comentario,'obrigatorio','texto');
        $this->comentario = $comentario;
    }

    public function getDataRealizacao():DateTime{
        return $this->dataRealizacao;
    }

    public function setDataRealizacao($dataRealizacao){
        Validacao::validar('Data de realização',$dataRealizacao,'data');
        $this->dataRealizacao = Formatacao::formataData($dataRealizacao);
    }

    public function getTotalGasto(){
        return $this->totalGasto;
    }

    public function setTotalGasto($totalGasto){
        Validacao::validar('TotalGasto',$totalGasto,'nuloOUmonetario');
        if (is_null($totalGasto)) {
            $this->totalGasto = $this->usuario->getValorHora() * $this->tempoGasto;
        }else{
            $this->totalGasto = $totalGasto;
        }
    }

    public function getUsuario():UsuarioModel{
        return $this->usuario;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

}