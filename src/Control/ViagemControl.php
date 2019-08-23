<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ViagemDao;
use Lasse\LPM\Model\ViagemModel;

class ViagemControl extends CrudControl {

    public function __construct($url)
    {
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new ViagemDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        switch ($this->metodo){
            case 'POST':
                $info = json_decode(file_get_contents("php://input"));
                // /api/viagens
                if (count($this->url) == 2) {

                }
                break;
            case 'GET':
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;

        }
    }

    public function cadastrar()
    {
        $veiculoControl = new VeiculoControl(null);
        if (isset($_POST['idVeiculo']) && $_POST['idVeiculo'] === 'novo'){
            $veiculoControl->cadastrar();
            $id = $veiculoControl->DAO->pdo->lastInsertId();
            $veiculo = $veiculoControl->listarPorId($id);
        }else{
            $veiculo = $veiculoControl->listarPorId($_POST['idVeiculo']);
        }

        //Cadastra viagem com total = 0
        try {
            $veiculo = 0;
            $viagem = new ViagemModel($_SESSION['usuario-classe'],$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'].' '.$_POST['horaEntradaHosp'],$_POST['dtSaidaHosp'].' '.$_POST['horaSaidaHosp'],null,null,null);
            echo $viagem->getEntradaHosp()->format('H:i:s');
        }
        catch (Exception $exception){
            $_SESSION['danger'] = $exception->getMessage();
        }
         /*$this->DAO->cadastrar($viagem,$_POST['idTarefa']);

        //Pega id da viagem inserida
        $idViagem = $this->DAO->pdo->lastInsertId();

        //cadastra os gastos relacionados a viagem
        $gastoControl = new GastoControl();
        $gastoControl->cadastrarGastos($_POST['gasto'],$idViagem);

        //atualiza total da viagem
        $this->atualizaTotal($idViagem);*/

    }

    public function atualizaTotal($idViagem)
    {
        $viagem = $this->DAO->listarPorId($idViagem);
        $this->DAO->atualizarTotal($viagem);
        $idTarefa = $this->DAO->descobrirIdTarefa($idViagem);
        $tarefaControl = new TarefaControl();
        $tarefaControl->atualizaTotal($idTarefa);
    }

    protected function atualizar()
    {
        $veiculoControl = new VeiculoControl();
        if (isset($_POST['idVeiculo']) && $_POST['idVeiculo'] === 'novo'){
            $veiculoControl->cadastrar();
            $id = $veiculoControl->DAO->pdo->lastInsertId();
            $veiculo = $veiculoControl->listarPorId($id);
        }else{
            $veiculo = $veiculoControl->listarPorId($_POST['idVeiculo']);
        }

        $funcDAO = new UsuarioControl();
        $viajante = $funcDAO->listarPorId($_POST['idFuncionario']);

        $viagem = new ViagemModel($viajante,$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'],$_POST['dtSaidaHosp'],$_POST['horaEntradaHosp'],$_POST['horaSaidaHosp'],$_POST['idViagem']);
        $this->DAO->atualizar($viagem);
    }


    protected function excluir(int $id)
    {
        $gastoControl = new GastoControl();
        $gastoControl->excluirPorIdViagem($id);
        $this -> DAO -> excluir($id);

        $tarefaControl = new TarefaControl();
        $tarefaControl->atualizaTotal($_POST['idTarefa']);
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    public function listarPorIdTarefa($idTarefa)
    {
        return $this->DAO->listarPorIdTarefa($idTarefa);
    }

    public function verificaPermissao(){

    }
}

