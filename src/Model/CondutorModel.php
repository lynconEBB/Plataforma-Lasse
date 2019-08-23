<?php

namespace Lasse\LPM\Model;

use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class CondutorModel
{
    private $id;
    private $nome;
    private $cnh;
    private $validadeCNH;

    public function __construct($nome, $cnh, $validadeCNH,$id=null){
        $this->setNome($nome);
        $this->setCnh($cnh);
        $this->setValidadeCNH($validadeCNH);
        $this->setId($id);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->id,
            "nome" => $this->nome,
            "cnh" => $this->cnh,
            "validadeCNH" => $this->validadeCNH->format('d/m/Y')
        ];
        return $array;
    }

    private function setId($id) {
        Validacao::validar("id",$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        Validacao::validar("Nome do Condutor",$nome,'obrigatorio','texto');
        $this->nome = $nome;
    }

    public function getCnh(){
        return $this->cnh;
    }

    public function setCnh($cnh){
        Validacao::validar("Número CNH",$cnh,'obrigatorio','texto');
        $this->cnh = $cnh;
    }

    public function getValidadeCNH(){
        $val = $this->validadeCNH;
        return $val;
    }

    public function setValidadeCNH($validadeCNH){
        Validacao::validar("Data de validade CNH",$validadeCNH,"data");
        $this->validadeCNH = Formatacao::formataData($validadeCNH);
    }

    public function getId(){
        return $this->id;
    }
}