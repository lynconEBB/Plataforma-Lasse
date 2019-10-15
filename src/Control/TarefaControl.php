<?php

namespace Lasse\LPM\Control;

use InvalidArgumentException;
use Lasse\LPM\Dao\TarefaDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Model\TarefaModel;
use UnexpectedValueException;

class TarefaControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new TarefaDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            $requisicaoEncontrada = false;
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/tarefas
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info);
                        $this->respostaSucesso("Tarefas Cadastrada com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/tarefas
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        if ($this->requisitor['admin'] == "1") {
                            $tarefas = $this->listar();
                            if ($tarefas) {
                                $this->respostaSucesso("Listado Tarefas",$tarefas,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhuma Tarefa Encontrada",null,$this->requisitor);
                            }
                        } else {
                            throw new PermissionException("Você não possui permissão para utilziar essa funcionalidade","Acessar todas as tarefas");
                        }
                    }
                    // /api/tarefas/{idTarefa}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $idProjeto = $this->descobrirIdProjeto($this->url[2]);
                        $projetoControl = new ProjetoControl(null);
                        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {
                            $this->requisitor['participa'] = true;
                            $tarefa = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listado tarefa",$tarefa,$this->requisitor);
                        }
                        else if ($this->requisitor['admin'] ==  "1") {
                            $this->requisitor['participa'] = false;
                            $tarefa = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listado tarefa",$tarefa,$this->requisitor);
                        }
                        else {
                            throw new PermissionException("Você não tem permissão para acessar esta tarefa","Acessar tarefas de projeto que não está inserido");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/tarefas/{idTarefa}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $tarefa = $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Tarefa atualizada com sucesso",$tarefa,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/tarefas/{idTarefa}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Tarefa excluida com sucesso",null, $this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    protected function cadastrar($info){
        if (isset($info->nome) && isset($info->estado) && isset($info->descricao) && isset($info->dataConclusao) && isset($info->dataInicio) && isset($info->idProjeto)){
            $projetoControl = new ProjetoControl(null);
            $projeto = $projetoControl->listarPorId($info->idProjeto);
            if ($projetoControl->procuraFuncionario($info->idProjeto,$this->requisitor['id'])) {
                $tarefa = new TarefaModel($info->nome,$info->descricao,$info->estado,$info->dataInicio,$info->dataConclusao,null,null,null,null,null);
                // Verifica se tarefa está no periodo de inicio e conclusão do projeto
                if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                    $this->DAO->cadastrar($tarefa,$info->idProjeto);
                } else {
                    throw new InvalidArgumentException('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
                }
            } else {
                throw new PermissionException("Você não tem permissao para adicionar uma tarefa neste projeto","Cadastrar tarefa em projeto que não está inserido");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }
    }

    protected function excluir($id)
    {
        $idProjeto = $this->descobrirIdProjeto($this->url[2]);
        $projetoControl = new ProjetoControl(null);
        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {
            $this->DAO->excluir($id);
            $projetoControl->atualizaTotal($idProjeto);
        } else {
            throw new PermissionException("Você não tem permissão para excluir esta tarefa","Excluir Tarefa em projeto que não está inserido");
        }
    }

    public function listar() {
        $tarefas = $this->DAO->listar();
        return $tarefas;
    }

    protected function atualizar($info,$id){
        if (isset($info->nome) && isset($info->estado) && isset($info->descricao) && isset($info->dataConclusao) && isset($info->dataInicio)){
            $projetoControl = new ProjetoControl(null);
            $idProjeto = $this->descobrirIdProjeto($id);
            if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {
                $tarefa = new TarefaModel($info->nome,$info->descricao,$info->estado,$info->dataInicio,$info->dataConclusao,$id,null,null,null,null);
                $projeto = $projetoControl->listarPorId($idProjeto);
                // Verifica se tarefa está no periodo de inicio e conclusão do projeto
                if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                    $this->DAO->atualizar($tarefa);
                } else {
                    throw new InvalidArgumentException('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
                }
            } else {
                throw new PermissionException("Você não tem permissão para atualizar esta tarefa","Atulizar dados de tarefa em projeto que não está inserido");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }

    }

    public function listarPorId($id){
        $tarefa = $this->DAO->listarPorId($id);
        if ($tarefa) {
            return $tarefa;
        } else {
            throw new NotFoundException("Tarefa não encontrada");
        }
    }

    public function descobrirIdProjeto($id){
        $idProjeto = $this->DAO->descobrirIdProjeto($id);
        if (is_null($idProjeto)) {
            throw new NotFoundException("Tarefa não encontrada");
        } else {
            return $idProjeto;
        }
    }

    public function atualizaTotal($idTarefa){
        $tarefa = $this->listarPorId($idTarefa);
        $this->DAO->atualizaTotal($tarefa);
        $idProjeto = $this->DAO->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $projetoControl->atualizaTotal($idProjeto);
    }
}
