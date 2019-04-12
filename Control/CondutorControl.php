<?php
require_once '../Model/Condutor.php';
require_once 'PadraoControl.php';
class CondutorControl extends PadraoControl {

    public function defineAcao($acao){
        echo 'Estou na classe filho';
     }

     public function falar(){
         parent::falar();
     }
}

$class = new CondutorControl();
$class::falar();