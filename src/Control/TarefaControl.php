<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\TarefaDao;
use Lasse\LPM\Model\TarefaModel;

class TarefaControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new TarefaDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/tarefas
                    if (count($this->url) == 2) {
                        $this->cadastrar($info);
                        $this->respostaSucesso("Tarefas Cadastrada com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/tarefas
                    if (count($this->url) == 2) {
                        if ($this->requisitor['admin'] == "1") {
                            $tarefas = $this->listar();
                            if ($tarefas) {
                                $this->respostaSucesso("Listado Tarefas",$tarefas,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhuma Tarefa Encontrada",null,$this->requisitor);
                            }
                        } else {
                            throw new Exception("Você não possui permissão para utilziar essa funcionalidade");
                        }
                    }
                    // /api/tarefas/{idTarefa}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $idProjeto = $this->descobrirIdProjeto($this->url[2]);
                        $projetoControl = new ProjetoControl(null);
                        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id']) || $this->requisitor['admin'] ==  "1") {
                            $tarefa = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listado tarefa",$tarefa,$this->requisitor);
                        } else {
                            throw new Exception("Você não tem permissão para acessar esta tarefa");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/tarefas/{idTarefa}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $tarefa = $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Tarefa atualizada com sucesso",$tarefa,$this->requisitor);

                    }
                    break;
                case 'DELETE':
                    // /api/tarefas/{idTarefa}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Tarefa excluida com sucesso",null, $this->requisitor);
                    }
                    break;
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
                    throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
                }
            } else {
                throw new Exception("Você não tem permissao para adicionar uma tarefa neste projeto");
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
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
            throw new Exception("Você não tem permissão para excluir esta tarefa");
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
                    throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto',401);
                }
            } else {
                throw new Exception("Você não tem permissão para atualizar esta tarefa");
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }

    }

    public function listarPorId($id){
        $tarefa = $this->DAO->listarPorId($id);
        if ($tarefa) {
            return $tarefa;
        } else {
            throw new Exception("Tarefa não encontrada");
        }
    }

    public function descobrirIdProjeto($id){
        $idProjeto = $this->DAO->descobrirIdProjeto($id);
        if (is_null($idProjeto)) {
            throw new Exception("Tarefa não encontrada");
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
