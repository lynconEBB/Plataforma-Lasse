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
                        $this->respostaSucesso("Atividade inserido com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/atividades
                    if (count($this->url) == 2) {
                        $atividades = $this->listar();
                        $this->respostaSucesso("Listando todas atividades",$atividades,$this->requisitor);
                    }
                    // /api/atividades/tarefa/{idTarefa}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'tarefa') {
                        $atividades = $this->listarPorIdTarefa($this->url[3]);
                        $this->respostaSucesso("Listando atividades da tarefa",$atividades,$this->requisitor);
                    }
                    // /api/atividades/user/{idusuario}
                    elseif (count($this->url) == 4 && $this->url[3] == (int)$this->url[3] && $this->url[2] == 'user') {
                        $atividades = $this->listarPorIdUsuario($this->url[3]);
                        $this->respostaSucesso("Listando atividades do usuário",$atividades,$this->requisitor);
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
    }

    protected function excluir(int $id)
    {
        $atividade = $this->listarPorId($id);
        if ($this->requisitor['id'] ==  $atividade->getUsuario()->getId()) {
            $this->DAO->excluir($id);
            $idTarefa = $this->DAO->descobrirIdTarefa($id);
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
        $atividade = $this->listarPorId($id);
        if ($atividade != false) {
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
            throw new Exception("Atividade não existente no sistema");
        }
    }

    public function listarPorId($id)
    {
        $atividade = $this->DAO->listarPorId($id);
        if ($atividade != false) {
            if ($this->requisitor['id'] ==  $atividade->getUsuario()->getId()) {
                return $atividade;
            } else {
                throw new Exception("Usuário não possui permissão ás funcionalidades desta atividade");
            }
        } else {
            throw new Exception("Atividade não existente no sistema");
        }

    }

    public function listarPorIdTarefa($idTarefa)
    {
        if ($this->verificaPermissao($idTarefa)) {
            $atividades = $this->DAO->listarPorIdTarefa($idTarefa);
            return $atividades;
        } else {
            throw new Exception("Usuário não possui permissão para listar atividades neste projeto");
        }
    }

    public function listarPorIdUsuario($idUsuario)
    {
        if ($this->requisitor['id'] == $idUsuario) {
            $atividades = $this->DAO->listarPorIdUsuario($idUsuario);
            return $atividades;
        } else {
            throw new Exception("Usuário não possui permissão para acessar as atividades deste usuario");
        }

    }

    public function verificaPermissao($idTarefa)
    {
        $tarefaControl = new TarefaControl(null);
        $idProjeto = $tarefaControl->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $resposta = $projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id']);
        return $resposta;
    }
}