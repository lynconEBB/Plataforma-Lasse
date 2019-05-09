<?php

require_once 'CrudControl.php';
require_once '../Model/Veiculo.php';
require_once '../DAO/VeiculoDAO.php';
require_once '../DAO/CondutorDAO.php';

class VeiculoControl extends CrudControl {

    public function __construct(){
        $this->DAO = new VeiculoDAO();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
                $this->cadastrar();
                break;
            case 2:
                $this->excluir($_GET['id']);
                break;
            case 3:
                $this->atualizar();
                break;
        }
    }

    protected function cadastrar(){
        $condDAO = new CondutorDAO();
        $condutor = $condDAO->listarPorId($_POST['idCondutor']);
        $veiculo = new Veiculo($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor);
        $this->DAO->cadastrar($veiculo);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $condDAO = new CondutorDAO();
        $condutor = $condDAO->listarPorId($_POST['idCondutor']);
        $veiculo = new Veiculo($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        $this -> DAO -> atualizar($veiculo);
    }

}
new VeiculoControl();