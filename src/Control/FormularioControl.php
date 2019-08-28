<?php

namespace Lasse\LPM\Control;

class FormularioControl extends CrudControl
{
    public function __construct($url)
    {

        parent::__construct($url);
    }

    public function processaRequisicao()
    {

        $teste = file_get_contents("php://std");
        var_dump($teste);
        $file = fopen("php://output",'w');
    }
}