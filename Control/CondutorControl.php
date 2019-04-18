<?php
require_once '../Model/Condutor.php';
require_once 'CrudControl.php';
class CondutorControl extends CrudControl {

    public function defineAcao($acao){
        switch ($acao){
            case 1:


        }
    }

    protected function cadastrar(){
        // TODO: Implement cadastrar() method.
    }

    protected function excluir(){
        // TODO: Implement excluir() method.
    }

    protected function listar(){
        // TODO: Implement listar() method.
    }

    protected function atualizar(){
        // TODO: Implement atualizar() method.
    }
}

$class = new CondutorControl();
