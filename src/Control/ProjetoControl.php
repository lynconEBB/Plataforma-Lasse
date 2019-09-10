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
                        if ($projetos != false ) {
                            $this->respostaSucesso("Listado Projetos",$projetos,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum projeto encontrado",null,$this->requisitor);
                        }
                    // /api/projetos/{idProjeto}
                    } elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->listarPorId($this->url[2]);
                        if ($this->procuraFuncionario($this->url[2],$this->requisitor['id']) || $this->requisitor['admin'] == "1") {
                            $projeto = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando Projeto",$projeto,$this->requisitor);
                        } else {

                            throw new Exception("Você precisa participar deste projeto para ter acesso à suas informações");
                        }
                    }
                    // /api/projetos/user/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'user') {
                        if ($this->requisitor['id'] == $this->url[3] || $this->requisitor['admin'] == "1") {
                            $projetos = $this->listarPorIdUsuario($this->url[3]);
                            if ($projetos != false) {
                                $this->respostaSucesso("Listando Projetos por Usuário",$projetos,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum projeto encontrado",null,$this->requisitor);
                            }
                        } else {
                            throw new Exception("Você não possui acesso aos projetos deste usuario");
                        }
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
        if (isset($info->dataFinalizacao) && isset($info->dataInicio) && isset($info->descricao) && isset($info->nome) && isset($info->centroCusto)) {
            $usuarioControl = new UsuarioControl(null);
            $dono = $usuarioControl->listarPorId($this->requisitor['id']);
            $projeto = new ProjetoModel($info->dataFinalizacao, $info->dataInicio, $info->descricao, $info->nome,$info->centroCusto, null, null, null, $dono);
            $this->DAO->cadastrar($projeto);
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",401);
        }

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
        if ($this->requisitor['admin'] == '1') {
            $projetos = $this->DAO->listar();
            return $projetos;
        } else {
            throw new Exception("Você precisa ser administrador para acessar essa funcionalidade");
        }
    }

    protected function atualizar($info,$id)
    {
        if (isset($info->dataFinalizacao) && isset($info->dataInicio) && isset($info->descricao) && isset($info->nome) && isset($info->centroCusto)) {
            if ($this->verificaDono($id, $this->requisitor['id'])) {
                $projeto = new ProjetoModel($info->dataFinalizacao, $info->dataInicio, $info->descricao, $info->nome,$info->centroCusto, $id, null, null, null);
                $this->DAO->alterar($projeto);
            } else {
                throw new Exception("Permissão negada para alterar este projeto",401);
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }
    }

    public function listarPorIdUsuario($id)
    {
        $projetos = $this->DAO->listarPorIdUsuario($id);
        return $projetos;
    }

    public function listarPorId($id)
    {
        $projeto = $this->DAO->listarPorId($id);
        if ($projeto != false) {
            return $projeto;
        } else {
            throw new Exception("Projeto não encontrado no sistema");
        }
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
        $this->listarPorId($idProjeto);
        $usuarioControl = new UsuarioControl(null);
        $usuarioControl->listarPorId($idUsuario);
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