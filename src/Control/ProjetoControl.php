<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ProjetoDao;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\ProjetoModel;

class ProjetoControl extends CrudControl
{
    private $requisitor;
    public function __construct($url)
    {
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new ProjetoDao();
        parent::__construct($url);
        $this->processaRequisicao();
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo) {
                case 'POST':
                    $info = json_encode(@file_get_contents("php://input"));
                    // /api/projetos
                    if (count($this->url) == 2) {
                        $this->cadastrar($info);
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
    }

    protected function cadastrar($info)
    {
        $usuarioControl = new UsuarioControl(null);

        $projeto = new ProjetoModel($info->dataFinalizacao, $info->dataInicio, $info->descricao, $info->nome, null, null, null, null);
        $this->DAO->cadastrar($projeto);
        $this->respostaSucesso("Projeto Cadastrado com sucesso",null,$this->requisitor);
    }

    protected function excluir($id)
    {
        $id = $this->url[3];
        $this->DAO->excluir($id);
        $this->respostaSucesso("Projeto excluído com sucesso",null,$this->requisitor);
    }

    public function listar()
    {
        return $this->DAO->listar();
    }

    protected function atualizar()
    {
        $projeto = new ProjetoModel($_POST['dataFinalizacao'], $_POST['dataInicio'], $_POST['descricao'], $_POST['nomeProjeto'], $_POST['id'], null, null, null);
        $this->DAO->alterar($projeto);
    }

    public function listarPorIdUsuario($id)
    {
        return $this->DAO->listarPorIdUsuario($id);
    }

    public function listarPorId($id)
    {
        return $this->DAO->listarPorId($id);
    }

    public function procuraFuncionario($idProjeto, $idUsuario)
    {
        $result = $this->DAO->procuraFuncionario($idProjeto, $idUsuario);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizaTotal($idProjeto)
    {
        $projeto = $this->DAO->listarPorId($idProjeto);
        $this->DAO->atualizarTotal($projeto);
    }

    public function verificaDono($idProjeto)
    {
        $numRows = $this->DAO->verificaDono($idProjeto);
        if ($numRows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addFuncionario()
    {
        if (!$this->procuraFuncionario($_POST['idProjeto'], $_POST['idUsuario'])) {
            $this->DAO->adicionarFuncionario($_POST['idUsuario'], $_POST['idProjeto']);
        } else {
            throw new Exception('Funcionário já inserido');
        }
    }
}