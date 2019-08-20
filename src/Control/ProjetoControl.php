<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ProjetoDao;
use Lasse\LPM\Model\ProjetoModel;

class ProjetoControl extends CrudControl
{
    public function __construct($url)
    {
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new ProjetoDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo) {
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/projetos
                    if (count($this->url) == 2) {
                        $this->cadastrar($info);
                        $this->respostaSucesso("Projeto Cadastrado com sucesso",null,$this->requisitor);
                    }
                    // /api/projetos/adicionar
                    elseif (count($this->url) == 3 && $this->url[2] == "adicionar") {
                        $this->addFuncionario($info->idProjeto,$info->idUsuario);
                        $this->respostaSucesso("Usuario adicionado",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/projetos
                    if (count($this->url) == 2) {
                        $projetos = $this->listar();
                        $this->respostaSucesso("Listado Projetos",$projetos,$this->requisitor);
                        // /api/projetos/{idProjeto}
                    } elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $projeto = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listando Projeto",$projeto,$this->requisitor);
                    }
                    // /api/projetos/user/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'user') {
                        $projetos = $this->listarPorIdUsuario($this->url[3]);
                        $this->respostaSucesso("Listando Projetos por Usuário",$projetos,$this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/projetos/{idProjeto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $projeto = $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Projeto atualizado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/projetos/{idProjeto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Projeto excluido com sucesso.",null,$this->requisitor);
                    }
                    break;
            }
        }
    }

    protected function cadastrar($info)
    {
        $usuarioControl = new UsuarioControl(null);
        $dono = $usuarioControl->listarPorId($this->requisitor['id']);
        $projeto = new ProjetoModel($info->dataFinalizacao, $info->dataInicio, $info->descricao, $info->nome, null, null, null, $dono);
        $this->DAO->cadastrar($projeto);
    }

    protected function excluir($id)
    {
        if ($this->verificaDono($id,$this->requisitor['id'])) {
            $this->DAO->excluir($id);
        } else {
            throw new Exception("Você precisa ser dono deste projeto para deleta-lo.");
        }
    }

    public function listar()
    {
        $projetos = $this->DAO->listar();
        return $projetos;
    }

    protected function atualizar($info,$id)
    {
        if ($this->verificaDono($id,$this->requisitor['id'])) {
            $projeto = new ProjetoModel($info->dataFinalizacao, $info->dataInicio, $info->descricao, $info->nome, $id, null, null, null);
            $this->DAO->alterar($projeto);
        } else {
            throw new Exception("Permissão negada para alterar este projeto");
        }

    }

    public function listarPorIdUsuario($id)
    {
        if ($this->requisitor['id'] == $id) {
            $projetos = $this->DAO->listarPorIdUsuario($id);
            return $projetos;
        } else {
            throw new Exception("Permissão negada");
        }

    }

    public function listarPorId($id)
    {
        $projeto = $this->DAO->listarPorId($id);
        return $projeto;
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

    public function verificaDono($idProjeto,$idUsuario)
    {
        $numRows = $this->DAO->verificaDono($idProjeto,$idUsuario);
        if ($numRows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addFuncionario($idProjeto,$idUsuario)
    {
        if ($this->verificaDono($idProjeto,$this->requisitor['id'])) {
            if (!$this->procuraFuncionario($idProjeto,$idUsuario)) {
                $this->DAO->adicionarFuncionario($idUsuario, $idProjeto);
            } else {
                throw new Exception('Funcionário já inserido');
            }
        } else {
            throw new Exception("Você não possui permissão para adicionar funcionários nesse projeto.");
        }

    }
}