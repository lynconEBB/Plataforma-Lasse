<?php

namespace Lasse\LPM\Model;

use DateTime;
use Exception;
use InvalidArgumentException;
use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class VeiculoModel
{
    private $id;
    private $nome;
    private $tipo;
    private $retirada;
    private $devolucao;
    private $condutor;

    public function __construct($nome, $tipo, $dataRetirada, $dataDevolucao, $condutor,$id=null){
        $this->setNome($nome);
        $this->setTipo($tipo);
        $this->setRetirada($dataRetirada);
        $this->setDevolucao($dataDevolucao);
        $this->setCondutor($condutor);
        $this->setId($id);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->id,
            "nome" => $this->nome,
            "tipo" => $this->tipo,
            "dataRetirada" => $this->retirada->format('d/m/Y'),
            "dataDevolucao" => $this->devolucao->format('d/m/Y'),
            "horarioRetirada" => $this->retirada->format('h:i'),
            "horarioDevolucao" => $this->devolucao->format('h:i'),
            "condutor" => $this->condutor->toArray()
        ];
        return $array;
    }

    private function setId($id)
    {
        Validacao::validar("Id",$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        Validacao::validar('Nome do Veiculo',$nome,'obrigatorio','texto');
        $this->nome = $nome;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        Validacao::validar('Tipo de Veiculo',$tipo,'obrigatorio','texto');
        $this->tipo = $tipo;
    }


    public function getCondutor():CondutorModel
    {
        return $this->condutor;
    }

    public function setCondutor(CondutorModel $condutor){
        $this->condutor = $condutor;
    }

    public function getRetirada()
    {
        return $this->retirada;
    }

    public function setRetirada($retirada)
    {
        Validacao::validar("Data e Hora Retirada",$retirada,'dataHora');
        $this->retirada = Formatacao::formataDataHora($retirada);
    }


    public function getDevolucao()
    {
        return $this->devolucao;
    }

    public function setDevolucao($devolucao)
    {
        Validacao::validar("Data e Hora de devolução",$devolucao,'dataHora');
        $devFormatada = Formatacao::formataDataHora($devolucao);
        if ($devFormatada > $this->retirada) {
            $this->devolucao = $devFormatada;
        } else {
            throw new InvalidArgumentException("O horario e data de devolução devem ser posteiores a retirada");
        }
    }




}
