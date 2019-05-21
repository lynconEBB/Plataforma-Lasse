<?php

require_once '../Services/Autoload.php';

class VeiculoControl extends CrudControl {

    public function __construct(){
        $this->DAO = new VeiculoDao();
        parent::__construct();
    }


    protected function cadastrar(){
        $condDAO = new CondutorDao();
        $condutor = $condDAO->listarPorId($_POST['idCondutor']);
        $veiculo = new VeiculoModel($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor);
        $this->DAO->cadastrar($veiculo);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $condDAO = new CondutorDao();
        $condutor = $condDAO->listarPorId($_POST['idCondutor']);
        $veiculo = new VeiculoModel($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        $this -> DAO -> atualizar($veiculo);
    }

}
new VeiculoControl();