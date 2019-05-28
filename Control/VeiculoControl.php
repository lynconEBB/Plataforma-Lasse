<?php

require_once '../Services/Autoload.php';

class VeiculoControl extends CrudControl {

    public function __construct(){
        $this->DAO = new VeiculoDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
                $this->cadastrar();
                header('Location:../View/VeiculoView.php');
                break;
            case 2:
                $this->excluir($_POST['id']);
                break;
            case 3:
                $this->atualizar();
                break;
        }
    }

    public function cadastrar(){
        $condControl = new CondutorControl();
        if ($_POST['idCondutor'] == 'novo'){
            $condControl->cadastrar();
            $id = $condControl->DAO->pdo->lastInsertId();
            $condutor = $condControl->listarPorId($id);
        }else{
            $condutor = $condControl->listarPorId($_POST['idCondutor']);
        }

        $veiculo = new VeiculoModel($_POST['nomeVeiculo'],$_POST['tipoVeiculo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor);
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

    public function listarPorId($id){
        return $this->DAO->listarPorId($id);
    }

}
LoginControl::verificar();
new VeiculoControl();