<?php

class VeiculoControl extends CrudControl {

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new VeiculoDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case "cadastrarVeiculo":
                $this->cadastrar();
                header('Location: /menu/veiculo');
                die();
            case 'excluirVeiculo':
                $this->excluir($_POST['id']);
                break;
            case 'alterarVeiculo':
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

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);
        header('Location: /menu/veiculo');
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    protected function atualizar(){
        $condControl = new CondutorControl();
        if ($_POST['idCondutor'] == 'novo'){
            $condControl->cadastrar();
            $id = $condControl->DAO->pdo->lastInsertId();
            $condutor = $condControl->listarPorId($id);
        }else{
            $condutor = $condControl->listarPorId($_POST['idCondutor']);
        }
        $veiculo = new VeiculoModel($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        $this -> DAO -> atualizar($veiculo);

        header('Location: /menu/veiculo');
    }

    public function listarPorId($id){
        return $this->DAO->listarPorId($id);
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'listaVeiculos':
                $condControl = new CondutorControl();
                $condutores = $condControl->listar();
                $veiculos = $this->listar();
                require '../View/telaVeiculo.php';

        }
    }
}
