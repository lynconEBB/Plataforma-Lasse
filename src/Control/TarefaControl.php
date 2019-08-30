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
                        $tarefas = $this->listar();
                        $this->respostaSucesso("Listado Tarefas",$tarefas,$this->requisitor);
                    }
                    // /api/tarefas/{idTarefa}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $tarefa = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listado tarefa",$tarefa,$this->requisitor);
                    }
                    // /api/tarefas/projeto/{idProjeto}
                    elseif (count($this->url) == 4 && $this->url[2] == 'projeto' && $this->url[3] == (int)$this->url[3]) {
                        $tarefas = $this->listarPorIdProjeto($this->url[3]);
                        $this->respostaSucesso("Listado Tarefas do projeto",$tarefas,$this->requisitor);
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
                    $info = json_decode(@file_get_contents("php://input"));
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
        $projetoControl = new ProjetoControl(null);
        if ($projetoControl->procuraFuncionario($info->idProjeto,$this->requisitor['id'])) {
            $tarefa = new TarefaModel($info->nome,$info->descricao,$info->estado,$info->dataInicio,$info->dataConclusao,null,null,null,null,null);
            $projeto = $projetoControl->listarPorId($info->idProjeto);
            // Verifica se tarefa está no periodo de inicio e conclusão do projeto
            if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                $this->DAO->cadastrar($tarefa,$info->idProjeto);
            } else {
                throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
            }
        } else {
            throw new Exception("Você não tem permissao para adicionar uma tarefa neste projeto");
        }

    }

    protected function excluir($id)
    {
        $projetoControl = new ProjetoControl(null);
        $idProjeto = $this->descobrirIdProjeto($id);
        if ($projetoControl->verificaDono($idProjeto,$this->requisitor['id'])) {
            $this->DAO->excluir($id);
            $projetoControl->atualizaTotal($idProjeto);
        } else {
            throw new Exception("Você não tem permissão para deletar uma tarefa deste projeto");
        }

    }

    public function listar() {
        $tarefas = $this->DAO->listar();
        return $tarefas;
    }

    protected function atualizar($info,$id){
        $projetoControl = new ProjetoControl(null);
        $idProjeto = $this->descobrirIdProjeto($id);

        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {

            $tarefa = new TarefaModel($info->nome,$info->descricao,$info->estado,$info->dataInicio,$info->dataConclusao,$id,null,null,null,null);
            $projeto = $projetoControl->listarPorId($idProjeto);
            if ($tarefa->getDataConclusao() > $projeto->getDataInicio() && $tarefa->getDataConclusao() < $projeto->getDataFinalizacao() && $tarefa->getDataInicio() > $projeto->getDataInicio() && $tarefa->getDataInicio() < $projeto->getDataFinalizacao() ){
                $this->DAO->atualizar($tarefa);
            } else {
                throw new Exception('O periodo de duração da tarefa precisa estar entre o periodo de duração do projeto');
            }
        }
    }

    public function listarPorIdProjeto($idProjeto){
        $projetoControl = new ProjetoControl(null);
        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {
            $tarefas = $this->DAO->listarPorIdProjeto($idProjeto);
            return $tarefas;
        } else {
            throw new Exception("Você não tem permissão para acessar estas tarefas");
        }
    }

    public function listarPorId($id){
        $idProjeto = $this->descobrirIdProjeto($id);
        $projetoControl = new ProjetoControl(null);
        if ($projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id'])) {
            $tarefa = $this->DAO->listarPorId($id);
            return $tarefa;
        } else {
            throw new Exception("Você não tem permissão para acessar esta tarefa");
        }
    }

    public function descobrirIdProjeto($id){
        $idProjeto = $this->DAO->descobrirIdProjeto($id);
        return $idProjeto;
    }

    public function atualizaTotal($idTarefa){
        $tarefa = $this->listarPorId($idTarefa);
        $this->DAO->atualizaTotal($tarefa);
        $idProjeto = $this->DAO->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $projetoControl->atualizaTotal($idProjeto);
    }
}
