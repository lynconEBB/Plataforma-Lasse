<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\AtividadeDao;
use Lasse\LPM\Model\AtividadeModel;

class AtividadeControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new AtividadeDao();
        parent::__construct($url);
    }
    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/atividades
                    if (count($this->url) == 2) {
                        $this->cadastrar($info);
                        $this->respostaSucesso("Atividade inserida com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/atividades
                    if (count($this->url) == 2) {
                        if ($this->requisitor['admin'] == "1") {
                            $atividades = $this->listar();
                            if ($atividades) {
                                $this->respostaSucesso("Listando todas atividades",$atividades,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhuma atividade foi encontrada",null,$this->requisitor);
                            }
                        } else {
                            throw new Exception("Você precisa ser administrador para ter acesso a esta funcionalidade",401);
                        }
                    }
                    // /api/atividades/{idAtividade}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $idTarefa = $this->DAO->descobrirIdTarefa($this->url[2]);
                        if ($this->verificaPermissao($idTarefa) || $this->requisitor['admin'] == "1") {
                            $atividade = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando atividaede",$atividade,$this->requisitor);
                        } else {
                            throw new Exception("Você não tem acesso a essa atividade");
                        }
                    }
                    // /api/atividades/user/{idusuario}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'user') {
                        $usuarioControl = new UsuarioControl(null);
                        $usuarioControl->listarPorId($this->url[3]);
                        if ($this->requisitor['id'] == $this->url[3] || $this->requisitor['admin'] == "1") {
                            $atividades = $this->listarPorIdUsuario($this->url[3]);
                            if ($atividades) {
                                $this->respostaSucesso("Listando atividades do usuário",$atividades,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum imprevisto encontrado!",null,$this->requisitor);
                            }
                        } else {
                            throw new Exception("Você não possui acesso as atividades deste usuário",401);
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/atividades/{idAtividade}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Atividade Atualizada com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/atividades/{idAtividade}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Atividade excluida com sucesso",null,$this->requisitor);
                    }
                    break;
            }
        }
    }

    public function cadastrar($info)
    {
        if (isset($info->tipo) && isset($info->tempoGasto) && isset($info->comentario) && isset($info->dataRealizacao)) {
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($this->requisitor['id']);
            $atividade = new AtividadeModel($info->tipo,$info->tempoGasto,$info->comentario,$info->dataRealizacao,$usuario,null,null);

            if(isset($info->idTarefa)){
                if ($this->verificaPermissao($info->idTarefa)) {
                    $this->DAO->cadastrar($atividade,$info->idTarefa);
                    $tarefaControl = new TarefaControl(null);
                    $tarefaControl->atualizaTotal($info->idTarefa);
                } else {
                    throw new Exception("Usuário não possui permissão para cadastrar atividades neste projeto");
                }
            }else{
                $this->DAO->cadastrar($atividade,null);
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }

    }

    protected function excluir($id)
    {
        $atividade = $this->listarPorId($id);
        if ($this->requisitor['id'] ==  $atividade->getUsuario()->getId()) {
            $idTarefa = $this->DAO->descobrirIdTarefa($id);
            $this->DAO->excluir($id);
            if(!is_null($idTarefa)) {
                $tarefaControl = new TarefaControl(null);
                $tarefaControl->atualizaTotal($idTarefa);
            }
        } else {
            throw new Exception("Usuário não possui permissão para excluir esta atividade");
        }
    }

    public function listar()
    {
        $atividades = $this->DAO->listar();
        return $atividades;
    }

    protected function atualizar($info,$id)
    {
        if (isset($info->tipo) && isset($info->tempoGasto) && isset($info->comentario) && isset($info->dataRealizacao)) {
            $atividade = $this->listarPorId($id);
            if ($this->requisitor['id'] ==  $atividade->getUsuario()->getId()) {
                $atividade = new AtividadeModel($info->tipo,$info->tempoGasto,$info->comentario,$info->dataRealizacao,$atividade->getUsuario(),$id,null);
                $this->DAO->atualizar($atividade);
                $idTarefa = $this->DAO->descobrirIdTarefa($id);
                if(!is_null($idTarefa)) {
                    $tarefaControl = new TarefaControl(null);
                    $tarefaControl->atualizaTotal($idTarefa);
                }
            } else {
                throw new Exception("Usuário não possui permissão para atualizar esta atividade");
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }
    }

    public function listarPorId($id)
    {
        $atividade = $this->DAO->listarPorId($id);
        if ($atividade != false) {
            return $atividade;
        } else {
            throw new Exception("Atividade não existente no sistema");
        }
    }

    public function listarPorIdUsuario($idUsuario)
    {
        $atividades = $this->DAO->listarPorIdUsuario($idUsuario);
        return $atividades;
    }

    //  Verifica se o requisitor esta presente no projeto
    public function verificaPermissao($idTarefa)
    {
        $tarefaControl = new TarefaControl(null);
        $idProjeto = $tarefaControl->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $resposta = $projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id']);
        return $resposta;
    }
}
