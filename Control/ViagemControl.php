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
            case 3:
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

        $funcDAO = new FuncionarioDao();
        $viajante = $funcDAO->listarPorId($_POST['idFuncionario']);
        
        $viagem = new ViagemModel($viajante,$veiculo,$_POST['origem'],$_POST['destino'],$_POST['dtIda'],$_POST['dtVolta'],$_POST['passagem'],$_POST['justificativa'],$_POST['observacoes'],$_POST['dtEntradaHosp'],$_POST['dtSaidaHosp'],$_POST['horaEntradaHosp'],$_POST['horaSaidaHosp'],'34');
        $this->DAO->cadastrar($viagem,$_POST['idTarefa']);

        $idViagem = $this->DAO->pdo->lastInsertId();

        $gastoControl = new GastoControl();
        $gastoControl->cadastrarGastos($_POST['gasto'],$idViagem);

        header("Location:../View/ViagemView.php?idTarefa=".$_POST['idTarefa']);

    }

    protected function excluir($id)
    {
        //$this -> Dao -> excluir($id);
    }

    public function listar()
    {
        //return $this -> Dao -> listar();
    }

    public function listarPorIdTarefa($idTarefa)
    {
        return $this -> DAO -> listarPorIdTarefa($idTarefa);
    }

    protected function atualizar()
    {
        //$condDAO = new CondutorDao();
        //$condutor = $condDAO->listarPorId($_POST['idCondutor']);
        //$veiculo = new VeiculoModel($_POST['nome'],$_POST['tipo'],$_POST['dtRetirada'],$_POST['dtDevolucao'],$_POST['horarioRetirada'],$_POST['horarioDevolucao'],$condutor,$_POST['id']);
        //$this -> Dao -> atualizar($veiculo);
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
