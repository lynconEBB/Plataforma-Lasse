<?php
require_once "../DAO/ViagemDAO.php";
require_once "../DAO/VeiculoDAO.php";
require_once "../Model/Viagem.php";

class ViagemControl extends CrudControl {

    public function __construct(){
        $this->DAO = new ViagemDAO();
        parent::__construct();
    }

    protected function cadastrar(){
        /*$veiculoDAO = new VeiculoDAO();
        $veiculo = $veiculoDAO->listarPorId($_POST['idVeiculo']);
        $veiculo = new Veiculo($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor);
        $this->DAO->cadastrar($veiculo);*/
    }

    protected function excluir($id){
        //$this -> DAO -> excluir($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        //$condDAO = new CondutorDAO();
        //$condutor = $condDAO->listarPorId($_POST['idCondutor']);
        //$veiculo = new Veiculo($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        //$this -> DAO -> atualizar($veiculo);
    }

}
new ViagemControl();