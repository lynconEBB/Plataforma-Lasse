<?php
class FormularioControl{
    private $odtFile;
    private $xml;
    private $estilos = array();
    private $html = '';
    private $atributos = array('style:font-name','style:column-width','style:vertical-align','fo:background-color','fo:padding','style:writing-mode','fo:margin-left','fo:margin-right',
        'fo:margin-top','fo:margin-bottom','fo:text-ident','fo:text-align','fo:font-size','fo:font-weight','fo:letter-spacing','fo:line-height');
    private $bordas = array('fo:border','fo:border-top','fo:border-left','fo:border-bottom','fo:border-right');
    private $cont =1;
    function __construct($file){

        $this->xml = new XMLReader();
        $this->odtFile =  $file;

        if($this->xml->open('zip://'.$this->odtFile.'#content.xml')){
            $this->gerarHtml();
        }
    }

    function gerarHtml(){
        while ($this->xml->read()){
            //echo $this->xml->hasValue;

            if(in_array($this->xml->nodeType ,array(XMLReader::ELEMENT, XMLReader::TEXT))){
                if($this->xml->name =='style:style' ){
                    $this->parseEstilo();
                }
                if($this->xml->name =='table:table' ) {
                     $this->parseTabela();
                }
            }

        }
    }
    public function parseTabela(){
        $this->html .= '<table border="5px solid #000000" align="center">';
        while($this->xml->read() && ($this->xml->name != "table:table" || $this->xml->nodeType != XMLReader::END_ELEMENT)) {
            if($this->xml->name == 'table:table-row' && $this->xml->nodeType == XMLReader::END_ELEMENT){
                $this->html .=  '</tr>';
            }
            elseif($this->xml->name == 'table:table-row'){
                $estiloRow = $this->xml->getAttribute('table:style-name');
                $estiloRow = str_replace('.','-',$estiloRow);
                $this->html .= '<tr class="'.$estiloRow.'">';

            }
            elseif($this->xml->name == 'table:table-cell' && $this->xml->nodeType == XMLReader::END_ELEMENT){
                $this->html .= '</td>';

            }
            elseif($this->xml->name == 'table:table-cell'){
                $colspan = $this->xml->getAttribute('table:number-columns-spanned');
                $estiloCell = $this->xml->getAttribute('table:style-name');
                $estiloCell = str_replace('.','-',$estiloCell);
                $this->html .= '<td class="'.$estiloCell.'" colspan="'.$colspan.'">';
                $this->cont +=1;

            }
            if($this->xml->name == 'text:p' && $this->xml->nodeType == XMLReader::END_ELEMENT){
                $this->html .= '</p>';
            }
            elseif ($this->xml->name == 'text:p'){
                $estiloTexto = $this->xml->getAttribute('text:style-name');
                $this->html .= '<p class="'.$estiloTexto.'">'.$this->xml->value;
            }

            if($this->xml->name == 'text:span' && $this->xml->nodeType == XMLReader::END_ELEMENT){
                $this->html .= '</span>';
            }
            elseif ($this->xml->name == 'text:span'){
                $estiloTexto = $this->xml->getAttribute('text:style-name');
                $this->html .= '<span class="'.$estiloTexto.'">'.$this->xml->value;
            }
            if($this->xml->name == '#text'){
                $this->html .= $this->xml->value;
            }


        }
    }

    public function parseEstilo(){
        $nome = $this->xml->getAttribute('style:name');
        $nome = str_replace('.','-',$nome);
        $this->estilos[$nome] = array();

        if($this->xml->getAttribute('style:parent-style-name')=='Standard'){
            $this->estilos[$nome]['background-color']= 'transparent';
            $this->estilos[$nome]['border-style'] ='none';
            $this->estilos[$nome]['font-size'] ='12pt';
            $this->estilos[$nome]['font-style'] ='normal';
            $this->estilos[$nome]['font-weight'] ='normal';
            $this->estilos[$nome]['line-height'] ='100%';
            $this->estilos[$nome]['padding'] ='0cm';
            $this->estilos[$nome]['text-align'] ='left ! important';
            $this->estilos[$nome]['font-family'] ='Liberation Serif';
            $this->estilos[$nome]['text-decoration'] ='none ! important';
            $this->estilos[$nome]['vertical-align'] ='top';
            $this->estilos[$nome]['writing-mode'] ='lr-tb';
            $this->estilos[$nome]['letter-spacing'] ='normal';
        }

        while($this->xml->read() && ($this->xml->name != "style:style" || $this->xml->nodeType != XMLReader::END_ELEMENT)){
            if($this->xml->name == 'style:table-cell-properties'){
                $partes = explode('-',$nome);
                $colunaNome = preg_replace('/[0-9]+/', '', $partes[1]);
                $colunaNome = $partes[0].'-'.$colunaNome;
                $this->estilos[$nome]['width'] = $this->estilos[$colunaNome]['width'];

            }
            foreach ($this->atributos as $atributo){
                if($this->xml->getAttribute($atributo) != null){
                    $this->estilos[$nome][$this->xml2html($atributo)] = $this->valor2html($this->xml->getAttribute($atributo));
                }
            }
            foreach ($this->bordas as $borda){
                if($this->xml->getAttribute($borda) != null){
                    $nomeBorda = $this->xml2html($borda);
                    if($this->xml->getAttribute($borda)=='none'){
                        $this->estilos[$nome][$nomeBorda] = 'none';
                    }else{
                        $attBordas = explode(' ',$this->xml->getAttribute($borda));
                        $this->estilos[$nome][$nomeBorda.'-width'] = $attBordas[0];
                        $this->estilos[$nome][$nomeBorda.'-style'] = $attBordas[1];
                        $this->estilos[$nome][$nomeBorda.'-color'] = $attBordas[2];
                    }
                }
            }
        }
    }

    public function xml2html($atributo){
        if($atributo == 'style:font:name'){
            return 'font-family';
        }elseif ($atributo == 'style:column-width'){
            return 'width';
        }else{
            $atributoParse = explode(':',$atributo);
            return $atributoParse[1];
        }
    }

    public function valor2html($valor){
        if($valor == 'start'){
            return 'left';
        }elseif ($valor == 'Arial1'){
            return 'Arial';
        }else{
            return $valor;
        }
    }

    public function get_estilos(){
        return $this->estilos;
    }
    public function get_html(){
        return $this->html;
    }
}
?>

