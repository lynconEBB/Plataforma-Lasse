<?php

namespace Lasse\LPM\Model;

class GastoModel
{
    private $id;
    private $valor;
    private $tipo;

    public function __construct($valor, $tipo,$id=null)
    {
        $this->id = $id;
        $this->valor = $valor;
        $this->tipo = $tipo;
    }

    public function getId(){
        return $this->id;
    }

    public function getValor(){
        return $this->valor;
    }

    public function setValor($valor){
        $this->valor = $valor;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
}

?>