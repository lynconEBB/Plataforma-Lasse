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

    private $email;
    private $atuacao;
    private $formacao;
    private $valorHora;

    public function __construct($nomeCompleto, $login, $senha, $dtNascimento, $cpf, $rg, $dtEmissao, $tipo, $email, $atuacao, $formacao, $valorHora,$id=null){
        $this->setId($id);
        $this->setNomeCompleto($nomeCompleto);
        $this->setLogin($login);
        $this->setSenha($senha);
        $this->setDtNascimento($dtNascimento);
        $this->setCpf($cpf);
        $this->setRg($rg);
        $this->setDtEmissao($dtEmissao);
        $this->setEmail($email);
        $this->setAtuacao($atuacao);
        $this->setFormacao($formacao);
        $this->setValorHora($valorHora);
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email)
    {
        Validacao::validar('E-mail',$email,'email');
        $this->email = $email;
    }

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function getNomeCompleto(){
        return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto){
        Validacao::validar('Nome Completo',$nomeCompleto,'obrigatorio','texto');

        $this->nomeCompleto = $nomeCompleto;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setSenha($senha){
        Validacao::validar('Senha',$senha,'nuloOUtexto');
        $this->senha = $senha;
    }

    public function getDtNascimento(){
        return $this->dtNascimento;
    }

    public function setDtNascimento($dtNascimento){
        Validacao::validar('E-mail',$email,'email');
        $this->dtNascimento = $dtNascimento;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        Validacao::validar('cpf',$cpf,'obrigatorio');
        $this->cpf = $cpf;
    }

    public function getRg(){
        return $this->rg;
    }

    public function setRg($rg){
        Validacao::validar('E-mail',$email,'email');
        $this->rg = $rg;
    }

    public function getDtEmissao(){
        return $this->dtEmissao;
    }

    public function setDtEmissao($dtEmissao){
        Validacao::validar('E-mail',$email,'email');
        $this->dtEmissao = $dtEmissao;
    }

    public function getLogin(){
        return $this->login;
    }

    public function setLogin($login){
        Validacao::validar('E-mail',$email,'email');
        $this->login = $login;
    }

    public function getAtuacao(){
        return $this->atuacao;
    }

    public function setAtuacao($atuacao){
        Validacao::validar('E-mail',$email,'email');
        $this->atuacao = $atuacao;
    }

    public function getFormacao(){
        return $this->formacao;
    }

    public function setFormacao($formacao){
        Validacao::validar('E-mail',$email,'email');
        $this->formacao = $formacao;
    }

    public function getValorHora(){
        return $this->valorHora;
    }

    public function setValorHora($valorHora){
        Validacao::validar('E-mail',$email,'email');
        $this->valorHora = $valorHora;
    }

}