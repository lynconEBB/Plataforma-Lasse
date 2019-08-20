<?php

namespace Lasse\LPM\Control;

use Exception;
use InvalidArgumentException;
use Lasse\LPM\Dao\CompraDao;
use Lasse\LPM\Model\CompraModel;
use Lasse\LPM\Model\UsuarioModel;

class CompraControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new CompraDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        switch ($this->metodo){
            case 'POST':
                $info = json_decode(@file_get_contents("php://input"));
                if (count($this->url) == 2) {
                    $this->cadastrar($info->proposito,$info->idTarefa,$info->itens);
                    $this->respostaSucesso("Compra cadastrada com sucesso",null, $this->requisitor);
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

    public function cadastrar($proposito,$idTarefa,$itens)
    {
        $usuarioControl = new UsuarioControl(null);
        $usuario = $usuarioControl->listarPorId($this->requisitor['id']);
        //Cadastra no banco de dados com Total = 0
        $compra = new CompraModel($proposito,null,null,null,$usuario);
        $this->DAO->cadastrar($compra,$idTarefa);
        //Pega id da Compra Inserida
        $idCompra = $this->DAO->pdo->lastInsertId();
        //Cadastra no banco todos os itens da Compra
        $itemControl = new ItemControl();
        foreach ($itens as $item) {
            $itemControl->cadastrar($item->valor,$item->nome,$item->quantidade,$idCompra);
        }
        //Atualiza total da Compra no banco
        $this->atualizarTotal($idCompra);

    }

    protected function excluir(int $id)
    {
        $this->DAO->excluir($id);
        $tarefaControl = new TarefaControl(null);
        $tarefaControl->atualizaTotal($_POST['idTarefa']);
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    public function listarPorIdTarefa($id)
    {
        return $this->DAO->listarPorIdTarefa($id);
    }

    public function listarPorId($id):CompraModel
    {
        return $this->DAO->listarPorId($id);
    }

    public function atualizarTotal($idCompra)
    {
        $compra = $this->DAO->listarPorId($idCompra);
        $this->DAO->atualizarTotal($compra);
        $idTarefa = $this->DAO->descobreIdTarefa($idCompra);
        $tarefaControl = new TarefaControl();
        $tarefaControl->atualizaTotal($idTarefa);
    }

    public function atualizar()
    {
        $compra = new CompraModel($_POST['proposito'],null,null,$_POST['id'],null);
        $this -> DAO -> atualizar($compra,$_POST['idTarefa']);
        $tarefaControl = new TarefaControl();
        $tarefaControl->atualizaTotal($_POST['idTarefa']);
        $tarefaControl->atualizaTotal($_POST['idTarefaAntiga']);
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
                return;
            }else{
                require '../View/errorPages/erroSemAcesso.php';
                exit();
            }
        }else{
            require '../View/errorPages/erroNaoSelecionado.php';
            exit();
        }
    }


}