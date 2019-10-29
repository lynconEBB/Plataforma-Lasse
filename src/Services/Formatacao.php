<?php

namespace Lasse\LPM\Services;

use DateTime;

class Formatacao
{
    public static function formataData($data)
    {
        $dataFormatada = new DateTime();
        if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/",$data)) {
            $partes = explode('/',$data);
            $dataFormatada->setDate($partes[2],$partes[1],$partes[0]);
        }
        elseif (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$data)){
            $partes = explode('-',$data);
            $dataFormatada->setDate($partes[0],$partes[1],$partes[2]);
        }
        return $dataFormatada;
    }

    public static function formataDataHora($dataHora)
    {
        $partes = explode(' ',$dataHora);
        $dataFormatada = self::formataData($partes[0]);
        $partesHorario = explode(':',$partes[1]);
        $dataFormatada->setTime($partesHorario[0],$partesHorario[1]);

        return $dataFormatada;
    }

    public static function formataMonetario($valor)
    {
        if (strpos($valor,",")) {
            $valor = str_replace(",",".",$valor);
        }

        return number_format($valor,2);
    }
}
