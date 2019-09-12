<?php


namespace Lasse\LPM\Services;


use DOMDocument;

class HtmlManipulator
{
    private $caminhoHTML;
    private $domDoc;

    public function __construct($arquivoHTML)
    {
        $this->caminhoHTML = $arquivoHTML;
        libxml_use_internal_errors(true);
        $this->domDoc = new DOMDocument('1.0', 'utf-8');
        $this->domDoc->loadHTMLFile($this->caminhoHTML );
    }

    public function consertaHTML()
    {
        $imgs = $this->domDoc->getElementsByTagName("img");
        foreach ($imgs as $img) {
            if (empty($img->getAttribute("width"))) {
                $img->setAttribute("width","100%");
            }
        }

        $tables = $this->domDoc->getElementsByTagName("table");
        foreach ($tables as $table) {
            $table->setAttribute("border","2px");
        }
    }

    public function salvar()
    {
        $body = $this->domDoc->getElementsByTagName("body")->item(0);
        $body =  $this->domDoc->saveHTML($body);
        $body = str_replace("<br>","",$body);
        file_put_contents($this->caminhoHTML ,$body);
    }
}
