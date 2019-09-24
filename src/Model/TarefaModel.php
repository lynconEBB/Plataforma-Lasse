<?php

namespace Lasse\LPM\Model;

use Exception;
use Lasse\LPM\Services\Formatacao;
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
        $this->setNome($nome);
        $this->setDescricao($descricao);
        $this->setEstado($estado);
        $this->setDataInicio($dataInicio);
        $this->setDataConclusao($dataConclusao);
        $this->setAtividades($atividades);
        $this->setViagens($viagens);
        $this->setCompras($compras);
        $this->setTotalGasto($totalGasto);
        $this->setId($id);
    }

    public function toArray()
    {
        if (is_array($this->atividades)) {
            $atividades = array();
            foreach ($this->atividades as $atividade) {
                $atividades[] = $atividade->toArray();
            }
        } else {
            $atividades = null;
        }
        if (is_array($this->compras)) {
            $compras = array();
            foreach ($this->compras as $compra) {
                $compras[] = $compra->toArray();
            }
        } else {
            $compras = null;
        }
        if (is_array($this->viagens)) {
            $viagens = array();
            foreach ($this->viagens as $viagem) {
                $viagens[] = $viagem->toArray();
            }
        } else {
            $viagens = null;
        }
        $array = [
            "id" => $this->id,
            "nome" => $this->nome,
            "descricao" => $this->descricao,
            "estado" => $this->estado,
            "dataInicio" => $this->dataInicio->format("d/m/Y"),
            "dataConclusao" => $this->dataConclusao->format("d/m/Y"),
            "atividades" => $atividades,
            "compras" => $compras,
            "viagens" => $viagens,
            "totalGasto" => $this->totalGasto,
        ];
        return $array;
    }

    public function calculaTotal()
    {
        $total = 0;
        if (is_array($this->atividades)){
            foreach ($this->atividades as $atividade){
                $total += $atividade->getTotalGasto();
            }
        }
        if (is_array($this->compras)){
            foreach ($this->compras as $compra){
                $total += $compra->getTotalGasto();
            }
        }
        if (is_array($this->viagens)){
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

    public function setTotalGasto($totalGasto)
    {
        if(is_null($totalGasto)){
            $this->calculaTotal();
        }else{
            Validacao::validar('Total Gasto',$totalGasto,'monetario');
            $this->totalGasto = $totalGasto;
        }
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
        $this->dataInicio = Formatacao::formataData($dataIncio);
    }

    public function getDataConclusao()
    {
        return $this->dataConclusao;
    }

    public function setDataConclusao($dataConclusao)
    {
        Validacao::validar('Data de Conclusão',$dataConclusao,'data');
        $dataformatada = Formatacao::formataData($dataConclusao);
        if ($dataformatada < $this->dataInicio) {
            throw new Exception('A data de Conclusão precisa ser posterior a data de Início');
        } else {
            $this->dataConclusao = $dataformatada;
        }
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
