<?php

namespace Lasse\LPM\Model;

use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class ItemModel
{
    private $id;
    private $valorParcial;
    private $valor;
    private $nome;
    private $quantidade;

    public function __construct($valor, $nome, $quantidade,$id=null){
        $this->setId($id);
        $this->setValor($valor);
        $this->setNome($nome);
        $this->setQuantidade($quantidade);
        $this->setValorParcial();
    }

    public function toArray()
    {
        $array = [
            "id" => $this->id,
            "nome" => $this->nome,
            "valor" => $this->valor,
            "quantidade" => $this->quantidade,
            "valorParcial" => $this->valorParcial
        ];
        return $array;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }


    public function getValor(){
        return $this->valor;
    }

    public function setValor($valor){
        Validacao::validar('Valor do item',$valor,'monetario');
        $this->valor = Formatacao::formataMonetario($valor);
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        Validacao::validar('Nome',$nome,'obrigatorio','texto');
        $this->nome = $nome;
    }

    public function getQuantidade(){
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        Validacao::validar('Quantidade de Itens',$quantidade,'monetario');
        $this->quantidade = $quantidade;
    }

    public function getValorParcial()
    {
        return $this->valorParcial;
    }

    public function setValorParcial()
    {
        $this->valorParcial = $this->valor * $this->quantidade;
    }

}