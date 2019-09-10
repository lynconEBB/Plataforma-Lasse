<?php


namespace Lasse\LPM\Services;


use DOMDocument;

class HtmlManipulator
{
    public function __construct($arquivoHTML)
    {

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTMLFile($arquivoHTML);

        $imgs = $dom->getElementsByTagName("img");
        foreach ($imgs as $img) {
            if (!empty($img->getAttribute("width"))) {
                $img->setAttribute("width","100%");
            }
        }
        $dom->saveHTMLFile($arquivoHTML);
    }
}