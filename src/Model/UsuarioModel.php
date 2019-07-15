<?php

namespace Lasse\LPM\Model;


use Lasse\LPM\Services\Validacao;

class UsuarioModel
{
    private $id;
    private $nomeCompleto;
    private $login;
    private $senha;
    private $dtNascimento;
    private $cpf;
    private $rg;
    private $dtEmissao;
    private $tipo;
    private $email;
    private $atuacao;
    private $formacao;
    private $valorHora;

    public function __construct($nomeCompleto, $login, $senha, $dtNascimento, $cpf, $rg, $dtEmissao, $tipo, $email, $atuacao, $formacao, $valorHora,$id=null){
        $this->setId($id);
        $this->setNomeCompleto($nomeCompleto);
        $this->login = $login;
        $this->senha = $senha;
        $this->dtNascimento = $dtNascimento;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->dtEmissao = $dtEmissao;
        $this->tipo = $tipo;
        $this->email = $email;
        $this->atuacao = $atuacao;
        $this->formacao = $formacao;
        $this->valorHora = $valorHora;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email)
    {
        Validacao::ehObrigatorio('Nome Completo',$email);

        $this->email = $email;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function getNomeCompleto(){
        return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto){
        Validacao::ehObrigatorio('Nome Completo',$nomeCompleto);

        $this->nomeCompleto = $nomeCompleto;
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
        Validacao::ehObrigatorio('cpf',$cpf);

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

    public function getLogin(){
        return $this->login;
    }

    public function setLogin($login){
        $this->login = $login;
    }

    public function getAtuacao(){
        return $this->atuacao;
    }

    public function setAtuacao($atuacao){
        $this->atuacao = $atuacao;
    }

    public function getFormacao(){
        return $this->formacao;
    }

    public function setFormacao($formacao){
        $this->formacao = $formacao;
    }

    public function getValorHora(){
        return $this->valorHora;
    }

    public function setValorHora($valorHora){
        $this->valorHora = $valorHora;
    }

}