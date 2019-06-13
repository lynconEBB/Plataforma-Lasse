<?php
require_once '../Services/Autoload.php';

class AtividadeControl extends CrudControl {

    public function __construct(){
        $this->DAO = new AtividadeDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastraAtividade':
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'excluiAtividade':
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'alteraAtividade':
                $this->atualizar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
        }
    }

    public function cadastrar()
    {
        $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe']);
        $this->DAO->cadastrar($atividade,$_POST['idTarefa']);

    }

    protected function excluir($id)
    {
        $this->DAO->excluir($id);
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    public function listarPorIdTarefa($id):array
    {
        return $this->DAO->listarPorIdTarefa($id);
    }

    protected function atualizar()
    {
        $atividade = new AtividadeModel($_POST['tipo'],$_POST['tempoGasto'],$_POST['comentario'],$_POST['dataRealizacao'],$_SESSION['usuario-classe'],$_POST['id']);
        $this -> DAO -> atualizar($atividade);
    }

    public function verificaPermissao()
    {
        if(isset($_GET['idTarefa'])){
            //Descobrindo id do Projeto em que a tarefa foi criada
            $tarefaControl = new TarefaControl();
            $idProjeto = $tarefaControl->descobrirIdProjeto($_GET['idTarefa']);

            // Verifica se o funcionário está relacionado com o Projeto
            $projetoControl = new ProjetoControl();

            if($projetoControl->procuraFuncionario($idProjeto) > 0){
                return true;
            }else{
                die("Você não possui acesso a essa Atividade<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
            }
        }else{
            die("Nenhuma Tarefa Selecionada!!<br>Selecione um projeto <a href='../View/ProjetoView.php'>aqui</a>");
        }
    }
}
LoginControl::verificar();
$class = new AtividadeControl();