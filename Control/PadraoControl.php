<?php


abstract class PadraoControl{
    public function __construct(){
        if( isset($_POST['acao']) ){
            $acao = $_POST['acao'];
            $this->defineAcao($acao);
        }elseif (isset($_GET['acao'])){
            $acao = $_GET['acao'];
            $this->defineAcao($acao);
        }
    }

    public function falar(){
        echo 'classe pai';
    }
    abstract protected function defineAcao($acao);

}