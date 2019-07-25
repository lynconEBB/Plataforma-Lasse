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
        if (!preg_match("/[0-9]{2}-[0-9]{2}-[0-9]{4}/",$valor) && !preg_match("/[0-9]{2}/[0-9]{2}/[0-9]{4}/",4535)) {
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser nulo ou texto');
        }
    }




}