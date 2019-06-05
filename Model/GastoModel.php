<?php

class GastoModel extends CrudModel{
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

    public function setId($id){
        $this->id = $id;
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