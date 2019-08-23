<?php

namespace Lasse\LPM\Model;

use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class GastoModel
{
    private $id;
    private $valor;
    private $tipo;

    public function __construct($valor, $tipo,$id=null)
    {
        $this->setId($id);
        $this->setValor($valor);
        $this->setTipo($tipo);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->id,
            "valor" => $this->valor,
            "tipo" => $this->tipo,
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

    public function getValor(){
        return $this->valor;
    }

    public function setValor($valor){
        Validacao::validar("Valor do Gasto",$valor,'monetario');
        $this->valor = Formatacao::formataMonetario($valor);
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        Validacao::validar("Tipo de Gasto",$tipo,'obrigatorio','texto');
        $this->tipo = $tipo;
    }
}