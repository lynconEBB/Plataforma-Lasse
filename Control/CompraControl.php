<?php

class CompraControl extends CrudControl {

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new CompraDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarCompra':
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'excluirCompra':
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'alterarCompra':
                $this->atualizar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
        }
    }

    public function cadastrar()
    {
        //Cadastra no banco de dados com Total = 0
        $compra = new CompraModel($_POST['proposito'],0);
        $this->DAO->cadastrar($compra,$_POST['idTarefa']);
        //Pega id da Compra Inserida
        $idCompra = $this->DAO->pdo->lastInsertId();

        //Cadastra no banco todos os itens da Compra
        $itemControl = new ItemControl();
        $itemControl->cadastrarPorArray($_POST['itens'],$idCompra);
        //pega os Itens inseridos
        $itens = $itemControl->listarPorIdCompra($idCompra);
        //Altera a compra para conter os itens e seu id
        $compra->setItens($itens);
        $compra->setId($idCompra);
        //Atualiza total da Compra no banco
        $this->DAO->atualizarTotal($compra);
    }

    protected function excluir($id)
    {
        $itemControl = new ItemControl();
        $itemControl->excluirPorIdCompra($id);
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

    public function listarPorId($id):CompraModel
    {
        return $this->DAO->listarPorId($id);
    }


    public function atualizarTotal($compra)
    {
        $this -> DAO -> atualizarTotal($compra);
    }

    public function atualizar()
    {
        $compra = new CompraModel($_POST['proposito'],null,$_POST['id']);
        $this -> DAO -> atualizar($compra,$_POST['idTarefa']);
    }


    public function verificaPermissao()
    {
        if(isset($_GET['idTarefa'])){
            //Descobrindo id do Projeto em que a tarefa foi criada
            $tarefaControl = new TarefaControl();
            $idProjeto = $tarefaControl->descobrirIdProjeto($_GET['idTarefa']);

            // Verifica se o funcionário está relacionado com o Projeto
            $projetoControl = new ProjetoControl();

            if($projetoControl->procuraFuncionario($idProjeto,$_SESSION['usuario-id']) > 0){
                return true;
            }else{
                require '../View/errorPages/erroSemAcesso.php';
                exit();
            }
        }else{
            require '../View/errorPages/erroNaoSelecionado.php';
            exit();
        }
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'listaCompras':
                $this->verificaPermissao();
                $tarefaControl = new TarefaControl();
                $tarefas = $tarefaControl->listarPorIdUsaurio($_SESSION['usuario-id']);
                $compras = $this->listarPorIdTarefa($_GET['idTarefa']);
                require '../View/CompraView.php';
                break;
        }
    }
}