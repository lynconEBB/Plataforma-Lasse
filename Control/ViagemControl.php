<?php
require_once '../Services/Autoload.php';

class ViagemControl extends CrudControl {

    public function __construct()
    {
        $this->DAO = new ViagemDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 4:
                $this->cadastrar();
                break;
            case 2:
                $this->excluir($_POST['id']);
                break;
            case "alterarViagem":
                $this->atualizar();
                break;
        }
    }


    protected function cadastrar()
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
        
        $viagem = new ViagemModel($viajante,$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'],$_POST['dtSaidaHosp'],$_POST['horaEntradaHosp'],$_POST['horaSaidaHosp']);
        $this->DAO->cadastrar($viagem,$_POST['idTarefa']);

        $idViagem = $this->DAO->pdo->lastInsertId();

        $gastoControl = new GastoControl();
        $gastoControl->cadastrarGastos($_POST['gasto'],$idViagem);

        header("Location:../View/ViagemView.php?idTarefa=".$_POST['idTarefa']);

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

        header('Location: '.$_SERVER['HTTP_REFERER']);
    }


    protected function excluir($id)
    {
        $this -> DAO -> excluir($id);
        header('Location: '.$_SERVER['HTTP_REFERER']);

    }

    public function listar()
    {
        return $this -> DAO -> listar();
    }

    public function listarPorIdTarefa($idTarefa)
    {
        return $this -> DAO -> listarPorIdTarefa($idTarefa);
    }


    public function verificaPermissao(){

        if(isset($_GET['idTarefa'])){
            //Descobrindo id do Projeto em que a tarefa foi criada
            $tarefaControl = new TarefaControl();
            $idProjeto = $tarefaControl->descobrirIdProjeto($_GET['idTarefa']);

            // Verifica se o funcionário está relacionado com o Projeto
            $projetoControl = new ProjetoControl();

            if($projetoControl->procuraFuncionario($idProjeto) > 0){
                return true;
            }else{
                die("Você não possui acesso a essa Viagem<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
            }
        }else{
            die("Nenhuma Tarefa Selecionada!!<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
        }
    }

}
LoginControl::verificar();
new ViagemControl();
