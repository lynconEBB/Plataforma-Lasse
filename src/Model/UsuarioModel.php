<?php

namespace Lasse\LPM\Model;

use DateTime;
use Lasse\LPM\Services\Formatacao;
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

    public function __construct($nomeCompleto, $login, $senha, $dtNascimento, $cpf, $rg, $dtEmissao, $email, $atuacao, $formacao, $valorHora,$id=null){
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
    public function toJSON()
    {
        $json = [
            "id" => $this->getId(),
            "nomeCompleto" => $this->getNomeCompleto(),
            "login" => $this->getLogin(),
            "dtNasc" => $this->getDtNascimento()->format('d/m/Y'),
            "cpf" => $this->getCpf(),
            "rg" => $this->getRg(),
            "dtEmissao" => $this->getDtEmissao()->format('d/m/Y'),
            "email" => $this->getEmail(),
            "atuacao" => $this->getAtuacao(),
            "formacao" => $this->getFormacao(),
            "valorHora" => $this->getValorHora()
        ];
        return json_encode($json);
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

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha){
        Validacao::validar('Senha',$senha,'nuloOUtexto');
        $this->senha = $senha;
    }

    public function getDtNascimento():DateTime{
        return $this->dtNascimento;
    }

    public function setDtNascimento($dtNascimento){
        Validacao::validar('Data de Nascimento',$dtNascimento,'data');
        $this->dtNascimento = Formatacao::formataData($dtNascimento);
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        Validacao::validar('CPF',$cpf,'cpf');
        $this->cpf = $cpf;
    }

    public function getRg(){
        return $this->rg;
    }

    public function setRg($rg){
        Validacao::validar('RG',$rg,'texto','obrigatorio');
        $this->rg = $rg;
    }

    public function getDtEmissao():DateTime{
        return $this->dtEmissao;
    }

    public function setDtEmissao($dtEmissao){
        Validacao::validar('Data de Emissão',$dtEmissao,'data');
        $this->dtEmissao = Formatacao::formataData($dtEmissao);
    }

    public function getLogin(){
        return $this->login;
    }

    public function setLogin($login){
        Validacao::validar('Nome de Usuário',$login,'texto',['minimo',6]);
        $this->login = $login;
    }

    public function getAtuacao(){
        return $this->atuacao;
    }

    public function setAtuacao($atuacao){
        Validacao::validar('Atuação',$atuacao,'obrigatorio','texto');
        $this->atuacao = $atuacao;
    }

    public function getFormacao(){
        return $this->formacao;
    }

    public function setFormacao($formacao){
        Validacao::validar('Formação',$formacao,'obrigatorio','texto');
        $this->formacao = $formacao;
    }

    public function getValorHora(){
        return $this->valorHora;
    }

    public function setValorHora($valorHora){
        Validacao::validar('Valor Hora',$valorHora,'monetario');
        $this->valorHora = Formatacao::formataMonetario($valorHora);
    }


}