<?php

namespace Lasse\LPM\Model;

use Lasse\LPM\Services\Validacao;

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

        foreach ($this->atividades as $atividade){
            $total += $atividade->getTotalGasto();
        }
        foreach ($this->compras as $compra){
            $total += $compra->getTotalGasto();
        }
        foreach ($this->viagens as $viagem){
            $total += $viagem->getTotalGasto();
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

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome)
    {
        Validacao::validar('Nome',$nome,'obrigatorio','texto');
        $this->nome = $nome;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        Validacao::validar('Descrição',$descricao,'obrigatorio','texto');
        $this->descricao = $descricao;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setEstado ($estado){
        Validacao::validar('Estado',$estado,'obrigatorio','texto');
        $this->estado = $estado;
    }

    public function getDataInicio(){
        return $this->dataInicio;
    }

    public function setDataInicio($dataIncio)
    {
        Validacao::validar('Data de Início',$dataIncio,'data');
        $this->dataIncio = $dataIncio;
    }

    public function getDataConclusao()
    {
        return $this->dataConclusao;
    }

    public function setDataConclusao($dataConclusao)
    {
        Validacao::validar('Data de Conclusão',$dataConclusao,'data');
        $this->dataConclusao = $dataConclusao;
    }

    public function getAtividades(){
        return $this->atividades;
    }

    public function setAtividades($atividades)
    {
        $this->atividades = $atividades;
    }

    public function getViagens(){
        return $this->viagens;
    }

    public function setViagens($viagens)
    {
        $this->viagens = $viagens;
    }

    public function getCompras(){
        return $this->compras;
    }

    public function setCompras($compras)
    {
        $this->compras = $compras;
    }
}