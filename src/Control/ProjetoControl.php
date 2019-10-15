<?php

namespace Lasse\LPM\Control;

use InvalidArgumentException;
use UnexpectedValueException;
use Lasse\LPM\Dao\ProjetoDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
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
            $requisicaoEncontrada = false;
            switch ($this->metodo) {
                case 'POST':
                    $body = json_decode(@file_get_contents("php://input"));
                    // /api/projetos
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($body);
                        $this->respostaSucesso("Projeto Cadastrado com sucesso",null,$this->requisitor);
                    }
                    // /api/projetos/adicionar
                    elseif (count($this->url) == 3 && $this->url[2] == "adicionar") {
                        $requisicaoEncontrada = true;
                        $this->addFuncionario($body->idProjeto,$body->idUsuario);
                        $this->respostaSucesso("Usuario adicionado",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/projetos
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $projetos = $this->listar();
                        if ($projetos != false ) {
                            $this->respostaSucesso("Listado Projetos",$projetos,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum projeto encontrado",null,$this->requisitor);
                        }
                    // /api/projetos/{idProjeto}
                    } elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->listarPorId($this->url[2]);
                        if ($this->procuraFuncionario($this->url[2],$this->requisitor['id']) || $this->requisitor['admin'] == "1") {
                            $projeto = $this->listarPorId($this->url[2]);
                            $dono = $this->verificaDono($projeto->getId(),$this->requisitor['id']);
                            $this->requisitor["dono"] = $dono;
                            $this->respostaSucesso("Listando Projeto",$projeto,$this->requisitor);
                        } else {
                            throw new PermissionException("Você precisa participar deste projeto para ter acesso à suas informações","Acessar Projeto que não está inserido");
                        }
                    }
                    // /api/projetos/user/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'user') {
                        $requisicaoEncontrada = true;
                        if ($this->requisitor['id'] == $this->url[3] || $this->requisitor['admin'] == "1") {
                            $projetos = $this->listarPorIdUsuario($this->url[3]);
                            if ($projetos != false) {
                                $serDono = array();
                                foreach ($projetos as $projeto) {
                                    $dono = $this->verificaDono($projeto->getId(),$this->requisitor['id']);
                                    $serDono[$projeto->getId()] = $dono;
                                }
                                $this->requisitor["infoAdd"] = $serDono;
                                $this->respostaSucesso("Listando Projetos por Usuário",$projetos,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum projeto encontrado!",null,$this->requisitor);
                            }
                        } else {
                            throw new PermissionException("Você não possui acesso aos projetos deste usuario","Acessar projetos de outro usuário");
                        }
                    }
                    break;
                case 'PUT':
                    $body = json_decode(@file_get_contents("php://input"));
                    // /api/projetos/{idProjeto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $projeto = $this->atualizar($body,$this->url[2]);
                        $this->respostaSucesso("Projeto atualizado com sucesso",null,$this->requisitor);
                    }
                    // /api/projetos/transferirDominio/{idProjeto}
                    elseif (count($this->url) == 4 && $this->url[2] == "transferirDominio" && $this->url[3] == (int)$this->url[3]) {
                        $requisicaoEncontrada = true;
                        $this->listarPorId($this->url[3]);
                        if ($this->verificaDono($this->url[3],$this->requisitor['id'])) {
                            $this->transferirDominio($this->url[3],$body);
                            $this->respostaSucesso("Dominio transferido com sucesso",null,$this->requisitor);
                        } else {
                            throw new PermissionException("Você precisa ser dono deste projeto","Transferir dominio de projeto que não é dono");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/projetos/{idProjeto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Projeto excluido com sucesso.",null,$this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function transferirDominio($idProjeto,$body)
    {
        if (isset($body->idNovoDono)) {
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($body->idNovoDono);
            if ($this->procuraFuncionario($idProjeto,$usuario->getId())) {
                $this->DAO->transferirDominio($idProjeto,$usuario->getId(),$this->requisitor['id']);
            } else {
                throw new InvalidArgumentException("Novo dono não encontrado no projeto");
            }
        } else {
            throw new UnexpectedValueException("Paramentros insuficientes ou mal estruturados");
        }
    }
    
    protected function cadastrar($body)
    {
        if (isset($body->dataFinalizacao) && isset($body->dataInicio) && isset($body->descricao) && isset($body->nome) && isset($body->centroCusto)) {
            $usuarioControl = new UsuarioControl(null);
            $dono = $usuarioControl->listarPorId($this->requisitor['id']);
            $projeto = new ProjetoModel($body->dataFinalizacao, $body->dataInicio, $body->descricao, $body->nome,$body->centroCusto, null, null, null, $dono);
            $this->DAO->cadastrar($projeto);
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }

    }

    protected function excluir($id)
    {
        if ($this->verificaDono($id,$this->requisitor['id'])) {
            $this->DAO->excluir($id);
        } else {
            throw new PermissionException("Você precisa ser dono deste projeto para deleta-lo.","Excluir projeto que não é dono");
        }
    }

    public function listar()
    {
        if ($this->requisitor['admin'] == '1') {
            $projetos = $this->DAO->listar();
            return $projetos;
        } else {
            throw new PermissionException("Você precisa ser administrador para acessar essa funcionalidade","Listar todos projetos");
        }
    }

    protected function atualizar($body,$id)
    {
        if (isset($body->dataFinalizacao) && isset($body->dataInicio) && isset($body->descricao) && isset($body->nome) && isset($body->centroCusto)) {
            if ($this->verificaDono($id, $this->requisitor['id'])) {
                $projeto = new ProjetoModel($body->dataFinalizacao, $body->dataInicio, $body->descricao, $body->nome,$body->centroCusto, $id, null, null, null);
                $this->DAO->alterar($projeto);
            } else {
                throw new PermissionException("Permissão negada para alterar este projeto","Atualizar projeto que não é dono");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
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
            throw new NotFoundException("Projeto não encontrado no sistema");
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
                throw new InvalidArgumentException('Funcionário já inserido');
            }
        } else {
            throw new PermissionException("Você não possui permissão para adicionar funcionários nesse projeto.","Inserir novos funcionários em projeto que não é dono");
        }
    }
}
