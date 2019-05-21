<?php
require_once '../Services/Autoload.php';

class ViagemControl extends CrudControl {

    public function __construct(){
        $this->DAO = new ViagemDao();
        parent::__construct();
    }

    protected function cadastrar() {
        $veiculoDAO = new VeiculoDao();
        $veiculo = $veiculoDAO->listarPorId($_POST['idVeiculo']);
        $funcDAO = new FuncionarioDao();
        $viajante = $funcDAO->listarPorId($_POST['idFuncionario']);
        
        $viagem = new ViagemModel($viajante,$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'],$_POST['dtSaidaHosp'],$_POST['horaEntradaHosp'],$_POST['horaSaidaHosp'],'34');
        $this->DAO->cadastrar($viagem,$_POST['idTarefa']);
    }

    protected function excluir($id){
        //$this -> Dao -> excluir($id);
    }

    public function listar(){
        //return $this -> Dao -> listar();
    }

    public function listarPorIdTarefa($idTarefa)
    {
        return $this -> DAO -> listarPorIdTarefa($idTarefa);
    }

    protected function atualizar(){
        //$condDAO = new CondutorDao();
        //$condutor = $condDAO->listarPorId($_POST['idCondutor']);
        //$veiculo = new VeiculoModel($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        //$this -> Dao -> atualizar($veiculo);
    }

}
new ViagemControl();
