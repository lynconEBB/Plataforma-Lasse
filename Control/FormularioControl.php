<?php
class FormularioControl{
    private $odtFile;
    private $xml;
    private $estilos = array();
    function __construct($file){

        $this->xml = new XMLReader();
        $this->odtFile =  $file;

        if($this->xml->open('zip://'.$this->odtFile.'#content.xml')){
            $this->gerarHtml();
        }
    }

    function gerarHtml(){
        while ($this->xml->read()){
            if(in_array($this->xml->nodeType ,array(XMLReader::ELEMENT, XMLReader::TEXT))){
                if($this->xml->name =='style:style' ){
                    $this->parseEstilo();
                }

            }
        }
    }

    function parseEstilo(){
        $estilo = ['name'=>$this->xml->getAttribute('style:name')];

        while($this->xml->read() && ($this->xml->name != "style:style" || $this->xml->nodeType != XMLReader::END_ELEMENT)){

            if($this->xml->name == 'style:table-column-properties'){
                $estilo['largura']=$this->xml->getAttribute('style:column-width');
            }
            elseif($this->xml->name =='style:table-row-properties'){
                $estilo['altura'] = $this->xml->getAttribute('style:min-row-height');
                $estilo['junto'] = $this->xml->getAttribute('fo:keep-together');
            }
            elseif($this->xml->name == 'style:table-cell-properties'){
                $estilo['valign'] = $this->xml->getAttribute('style:vertical-align');
                $estilo['padding']= $this->xml->getAttribute('fo:padding');
                $estilo['border-left'] = $this->xml->getAttribute('fo:border-left');
                $estilo['border-right'] = $this->xml->getAttribute('fo:border-right');
                $estilo['border-top'] = $this->xml->getAttribute('fo:border-top');
                $estilo['border-bottom'] = $this->xml->getAttribute('fo:border-bottom');
                $estilo['background-color'] =  $this->xml->getAttribute('fo:background-color');
            }
            elseif($this->xml->name == 'style:paragraph-properties'){
                $estilo['text-align'] = $this->xml->getAttribute('fo:text-align');
                $estilo['background-color'] =  $this->xml->getAttribute('fo:background-color');
                $estilo['line_height'] =  $this->xml->getAttribute('fo:line-height');
                $estilo['margin-left'] =  $this->xml->getAttribute('fo:margin-left');
                $estilo['margin-right'] =  $this->xml->getAttribute('for:margin-right');
                $estilo['margin-top'] =  $this->xml->getAttribute('fo:margin-top');
                $estilo['margin-bottom'] =  $this->xml->getAttribute('fo:margin-bottom');
            }
        }
        $this->estilos[] = $estilo;
    }
}
new FormularioControl($_FILES["arquivo"]["tmp_name"]);
?>

