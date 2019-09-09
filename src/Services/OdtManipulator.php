<?php


namespace Lasse\LPM\Services;


use Exception;
use SplFileObject;
use XMLReader;
use ZipArchive;

class OdtManipulator
{
    public $contentXML;
    public $tempContent;
    public $stylesXML;
    public $tempStyles;
    public $arquivoOdt;

    public function __construct($arquivoOdt)
    {
        $this->arquivoOdt = new ZipArchive();
        if ($this->arquivoOdt->open($arquivoOdt) === true) {
            $this->contentXML = $this->arquivoOdt->getFromName("content.xml");
            $this->stylesXML = $this->arquivoOdt->getFromName("styles.xml");
        } else {
            throw new Exception("Erro durante Criação de arquivo");
        }
    }

    public function setCampo($nome,$valor)
    {
        $this->contentXML = str_replace('{'.$nome.'}',$valor,$this->contentXML);
        $this->stylesXML = str_replace('{'.$nome.'}',$valor,$this->stylesXML);
    }

    public function __destruct()
    {
        $this->tempContent = tempnam(sys_get_temp_dir(),'');
        $this->tempStyles = tempnam(sys_get_temp_dir(),'');

        file_put_contents($this->tempStyles,$this->stylesXML);
        file_put_contents($this->tempContent,$this->contentXML);

        $this->arquivoOdt->deleteName("content.xml");
        $this->arquivoOdt->deleteName("styles.xml");

        $this->arquivoOdt->addFile($this->tempStyles,"styles.xml");
        $this->arquivoOdt->addFile($this->tempContent,"content.xml");

    }


}