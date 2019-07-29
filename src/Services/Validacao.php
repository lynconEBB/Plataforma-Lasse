<?php


namespace Lasse\LPM\Services;

use InvalidArgumentException;

abstract class Validacao
{
    public static function validar($nomeParametro,$valor, ...$validacoes)
    {
        foreach ($validacoes as $validacao){
            if(is_array($validacao)){
                $funcao = $validacao[0];
                self::$funcao($nomeParametro,$valor,$validacao[1]);
            }else{
                self::$validacao($nomeParametro,$valor);
            }
        }
    }

    private static function obrigatorio($nomeParametro,$valor)
    {
        if (empty($valor)){
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser preenchido');
        }
    }

    private static function texto($nomeParametro,$valor)
    {
        if (!is_string($valor)) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser um texto');
        }
    }

    private static function semEspaco($nomeParametro,$valor)
    {
        if (strpos($valor,' ') !== false) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' não pode conter espaços');
        }
    }

    private static function email($nomeParametro,$valor)
    {
        if (!filter_var($valor,FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException($nomeParametro.' inválido');
        }
    }

    private static function nuloOUinteiro($nomeParametro,$valor)
    {
        if (is_integer($valor) || is_null($valor)){
            return;
        }

        if (!is_numeric($valor) || $valor != (int)$valor) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser um numero inteiro ou nulo');
        }
    }

    private static function minimo($nomeParametro,$valor,$tamanhoMinimo)
    {
        if (strlen($valor) < $tamanhoMinimo) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ter no minimo '.$tamanhoMinimo.' caracteres');
        }
    }

    private static function nuloOUtexto($nomeParametro,$valor)
    {
        if (!is_null($valor) && !is_string($valor)) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser nulo ou texto');
        }
    }

    private static function data($nomeParametro,$valor)
    {
        if (!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$valor) && !preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/",$valor)) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve estar no formato de data');
        }
    }

    private static function cpf($nomeParametro,$valor)
    {

        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $valor );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf) || !preg_match("/[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}/",$valor)) {
            throw new InvalidArgumentException($nomeParametro.' inválido');
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                throw new InvalidArgumentException($nomeParametro.' inválido');
            }
        }
    }

    private static function monetario($nomeParametro,$valor)
    {
        if(substr_count($valor,',') > 1 || !is_numeric(str_replace(',','',$valor)) || str_replace(',','',$valor) < 0){
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve estar no formato monetário');
        }
        if(substr_count($valor,',') > 0 && substr_count($valor,'.') > 0){
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve estar no formato monetário');
        }
    }



}