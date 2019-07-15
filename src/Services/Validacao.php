<?php


namespace Lasse\LPM\Services;


use InvalidArgumentException;

abstract class Validacao
{
    public static function ehObrigatorio($nomeParametro,$valor) {
        if (empty($valor)){
            throw new InvalidArgumentException('O campo '.$nomeParametro.' deve ser preenchido');
        }else{
            return;
        }
    }
}