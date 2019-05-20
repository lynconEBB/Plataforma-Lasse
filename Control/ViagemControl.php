<?php
require_once "CrudControl.php";
require_once "../DAO/ViagemDAO.php";
require_once "../DAO/VeiculoDAO.php";
require_once "../Model/Viagem.php";

class ViagemControl extends CrudControl {

    public function __construct(){
        $this->DAO = new ViagemDAO();
        parent::__construct();
    }

    protected function cadastrar(){
        $veiculoDAO = new VeiculoDAO();
        $veiculo = $veiculoDAO->listarPorId($_POST['idVeiculo']);
        $funcDAO = new FuncionarioDAO();
        $viajante = $funcDAO->listarPorId($_POST['idFuncionario']);
        
        $viagem = new Viagem($viajante,$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'],$_POST['dtSaidaHosp'],$_POST['horaEntradaHosp'],$_POST['horaSaidaHosp'],'34');
        $this->DAO->cadastrar($viagem,$_POST['idTarefa']);
    }

    protected function excluir($id){
        //$this -> DAO -> excluir($id);
    }

    public function listar(){
        //return $this -> DAO -> listar();
    }

    public function listarPorIdTarefa($idTarefa){
        return $this -> DAO -> listarPorIdTarefa($idTarefa);
    }

    protected function atualizar(){
        //$condDAO = new CondutorDAO();
        //$condutor = $condDAO->listarPorId($_POST['idCondutor']);
        //$veiculo = new Veiculo($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        //$this -> DAO -> atualizar($veiculo);
    }

}
new ViagemControl();