<?php

namespace Lasse\LPM\Model;

use DateTime;
use Exception;
use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

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
        $this->setId($id);
        $this->setDataInicio($dataInicio);
        $this->setDataFinalizacao($dataFinalizacao);
        $this->setDescricao($descricao);
        $this->setNome($nome);
        $this->setTarefas($tarefas);
        $this->setParticipantes($participantes);
        $this->settotalGasto($totalGasto);
    }

    public function toArray()
    {
        if (is_array($this->tarefas)) {
            $tarefas = array();
            foreach ($this->tarefas as $tarefa) {
                $tarefas[] = $tarefa->toArray();
            }
        } else {
            $tarefas = null;
        }
        if (is_array($this->participantes)) {
            $participantes = array();
            foreach ($this->participantes as $participante) {
                $participantes[] = $participante->toArray();
            }
        } else {
            $participantes = null;
        }
        $array = [
            "id" => $this->id,
            "dataInicio" => $this->dataInicio->format("d/m/Y"),
            "dataFinalizacao" => $this->dataFinalizacao->format("d/m/Y"),
            "nome" => $this->nome,
            "descricao" => $this->descricao,
            "tarefas" => $tarefas,
            "totalGasto" => $this->totalGasto,
            "participantes" => $participantes
        ];
        return $array;
    }

    public function calculaTotal()
    {
        $total = 0.00;
        if (is_array($this->tarefas)) {
            foreach ($this->tarefas as $tarefa){
                $total += $tarefa->getTotalGasto();
            }
        }
        $this->totalGasto = $total;
    }

    public function getParticipantes()
    {
        return $this->participantes;
    }

    public function setParticipantes($participantes)
    {
        $this->participantes = $participantes;
    }

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDataFinalizacao():DateTime
    {
        return $this->dataFinalizacao;
    }

    public function setDataFinalizacao($dataFinalizacao)
    {

        Validacao::validar('Data de Finalização',$dataFinalizacao,'data');
        $dataformatada = Formatacao::formataData($dataFinalizacao);
        if ($dataformatada < $this->dataInicio) {
            throw new Exception('A data de finalização não pode ser anterior a data de Início');
        }else {
            $this->dataFinalizacao = $dataformatada;
        }

    }

    public function getDataInicio():DateTime{
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio)
    {
        Validacao::validar('Data de Início',$dataInicio,'data');
        $this->dataInicio = Formatacao::formataData($dataInicio);
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

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        Validacao::validar('Descrição',$descricao,'obrigatorio','texto');
        $this->descricao = $descricao;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome)
    {
        Validacao::validar('Nome',$nome,'obrigatorio','texto');
        $this->nome = $nome;
    }

    public function getTarefas(){
        return $this->tarefas;
    }

    public function setTarefas($tarefas)
    {
        $this->tarefas = $tarefas;
    }
}
