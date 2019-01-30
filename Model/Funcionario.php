<?php
/**
 * Created by PhpStorm.
 * User: lyncon
 * Date: 16/01/19
 * Time: 09:08
 */

class Funcionario{
    private $id;
    private $nomeCompleto;
    private $usuario;
    private $senha;
    private $dtNascimento;
    private $cpf;
    private $rg;
    private $dtEmissao;
    private $tipo;
    private $email;

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getNomeCompleto(){
        return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto){
        $this->nomeCompleto = $nomeCompleto;
    }

    public function getUsuario(){
        return $this->usuario;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setSenha($senha){
        $this->senha = $senha;
    }

    public function getDtNascimento(){
        return $this->dtNascimento;
    }

    public function setDtNascimento($dtNascimento){
        $this->dtNascimento = $dtNascimento;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }

    public function getRg(){
        return $this->rg;
    }

    public function setRg($rg){
        $this->rg = $rg;
    }

    public function getDtEmissao(){
        return $this->dtEmissao;
    }

    public function setDtEmissao($dtEmissao){
        $this->dtEmissao = $dtEmissao;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }

}