<?php


namespace Lasse\LPM\Services;


use Exception;
use XMLReader;
use ZipArchive;

class OdtManipulator
{
    public $arquivoODT;
    public $pastaExtraida;
    public $arquivoContentXML;
    public $stylesXML;
    public $contentXML;

    public function __construct($arquivo,$pasta)
    {
        $this->arquivoODT = $arquivo;
        $this->pastaExtraida = $pasta;
        $this->arquivoContentXML = $pasta."/content.xml";
        $this->stylesXML = $pasta."/styles.xml";
    }

    public function unzipODT()
    {
        $zip = new ZipArchive();
        $res = $zip->open($this->arquivoODT);
        if ( $res === true) {
            $zip->extractTo($this->pastaExtraida);
            $zip->close();
        } else {
            throw new Exception("Não foi possivel abrir o arquivo odt");
        }
    }

    public function setCampo($nome,$valor) {
        if ($this->contentXML == "") {
            $this->contentXML = fopen($this->arquivoContentXML,'rw');
            $valor = readfile($this->arquivoContentXML);

            echo $valor;
        }

        str_replace('{'.$nome.'}',$valor,$this->contentXML);
    }

    public function zipSave()
    {
        file_put_contents($this->arquivoContentXML,$this->contentXML);
        echo $this->contentXML;
    }

}