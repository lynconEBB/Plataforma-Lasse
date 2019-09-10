<?php


namespace Lasse\LPM\Services;


use DOMDocument;

class HtmlManipulator
{
    public function __construct($arquivoHTML)
    {
        $domDoc = new DOMDocument();
        $conteudo = file_get_contents($arquivoHTML);
        @$domDoc->loadHTML($conteudo);

        var_dump($domDoc);

    }
}