<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ViagemDao;
use Lasse\LPM\Model\ViagemModel;
use stdClass;

class ViagemControl extends CrudControl {

    public function __construct($url)
    {
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new ViagemDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (is_array($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(file_get_contents("php://input"));
                    // /api/viagens
                    if (count($this->url) == 2) {
                        if ($this->verificaDados($info,'cadastro')) {
                            $this->cadastrar($info);
                            $this->respostaSucesso("Viagem cadastrada com sucesso",null,$this->requisitor);
                        } else {
                            throw new Exception("Requisição com parâmetros faltando ou mal estruturados");
                        }
                    }
                    break;
                case 'GET':
                    // /api/viagens
                    if (count($this->url) == 2) {
                        if ($this->requisitor['admin'] == "1") {
                            $viagens = $this->listar();
                            if ($viagens != false) {
                                $this->respostaSucesso("Listando todas Viagens",$viagens,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum Viagem encontrada no sistema",null,$this->requisitor);
                                http_response_code(201);
                            }

                        } else {
                            throw new Exception("Você precisa ser administrador para ter acesso a todas as viagens");
                        }
                    }
                    // /api/viagens/{idViagem}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $viagem = $this->listarPorId($this->url[2]);
                        if ($this->requisitor['id'] == $viagem->getViajante()->getId() || $this->requisitor['admin'] == "1") {
                            $this->respostaSucesso("Listando Viagem",$viagem,$this->requisitor);
                        } else {
                            throw new Exception("Você não possui acesso aos detalhes desta viagem");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(file_get_contents("php://input"));
                    // /api/viagens/{idViagem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        if ($this->verificaDados($info,'atualizacao')){
                            $this->atualizar($info,$this->url[2]);
                            $this->respostaSucesso("Viagem atualizada com sucesso",null,$this->requisitor);
                        } else {
                            throw new Exception("Requisição com parâmetros faltando ou mal estruturados na viagem");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/viagens/{idViagem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Viagem Excluida com sucesso",null,$this->requisitor);
                    }
                    break;
            }
        }
    }

    public function cadastrar($dados)
    {
        if ($this->verificaPermissao($dados->idTarefa)) {
            $veiculoControl = new VeiculoControl(null);
            if ($dados->veiculo instanceof stdClass){
                $veiculoControl->cadastrar($dados->veiculo);
                $id = $veiculoControl->DAO->pdo->lastInsertId();
                $veiculo = $veiculoControl->listarPorId($id);
            }else{
                $veiculo = $veiculoControl->listarPorId($dados->veiculo);
            }

            // Cria Usuario que esta querendo fazer a viagem
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($this->requisitor['id']);

            //Cadastra viagem com total = 0
            $viagem = new ViagemModel($usuario,$veiculo,$dados->origem,$dados->destino,$dados->dataIda,$dados->dataVolta,
                $dados->passagem,$dados->justificativa,$dados->observacoes,$dados->dtEntradaHosp.' '.$dados->horaEntradaHosp,
                $dados->dtSaidaHosp.' '.$dados->horaSaidaHosp,$dados->fonte,$dados->meta,$dados->atividade,$dados->tipoPassagem,$dados->tipo,
                null,null,null);
            $this->DAO->cadastrar($viagem,$dados->idTarefa);

            //Pega id da viagem inserida
            $idViagem = $this->DAO->pdo->lastInsertId();

            //cadastra os gastos relacionados a viagem
            $gastoControl = new GastoControl(null);
            foreach ($dados->gastos as $gasto) {
                $gastoControl->cadastrar($gasto,$idViagem);
            }
        } else {
            throw new Exception("Você não possui permissão para cadastrar viagens nessa tarefa");
        }
    }

    public function atualizaTotal($idViagem)
    {
        $viagem = $this->DAO->listarPorId($idViagem);
        $this->DAO->atualizarTotal($viagem);
        $idTarefa = $this->DAO->descobrirIdTarefa($idViagem);
        $tarefaControl = new TarefaControl(null);
        $tarefaControl->atualizaTotal($idTarefa);
    }

    protected function atualizar($dados,$id)
    {
        $viagem = $this->listarPorId($id);
        if ($viagem->getViajante()->getId() == $this->requisitor['id']) {
            $veiculoControl = new VeiculoControl(null);
            if ($dados->veiculo instanceof stdClass){
                $veiculoControl->cadastrar($dados->veiculo);
                $idVeiculo = $veiculoControl->DAO->pdo->lastInsertId();
                $veiculo = $veiculoControl->listarPorId($idVeiculo);
            }else{
                $veiculo = $veiculoControl->listarPorId($dados->idVeiculo);
            }

            $funcDAO = new UsuarioControl(null);
            $viajante = $funcDAO->listarPorId($this->requisitor['id']);

            $viagem = new ViagemModel($viajante,$veiculo,$dados->origem,$dados->destino,$dados->dataIda,$dados->dataVolta,$dados->passagem,$dados->justificativa,$dados->observacoes,$dados->dtEntradaHosp.' '.$dados->horaEntradaHosp,$dados->dtSaidaHosp.' '.$dados->horaSaidaHosp,$dados->fonte,$dados->meta,$dados->atividade,$dados->tipoPassagem,$dados->tipo,null,$id,null);
            $this->DAO->atualizar($viagem);
        } else {
            throw new Exception("Você não possui permissão para atualizar esta viagem");
        }

    }

    protected function excluir($id)
    {
        $viagem = $this->listarPorId($id);
        if ($viagem->getViajante()->getId() == $this->requisitor['id']) {
            $idTarefa = $this->DAO->descobrirIdTarefa($id);
            $this->DAO->excluir($id);
            $tarefaControl = new TarefaControl(null);
            $tarefaControl->atualizaTotal($idTarefa);
        } else {
            throw new Exception("Você não possui permissão para excluir esta viagem");
        }
    }

    public function listar()
    {
        $viagens = $this->DAO->listar();
        return $viagens;
    }

    public function listarPorId($id)
    {
        $viagem = $this->DAO->listarPorId($id);;
        if ($viagem != false) {
            return $viagem;
        } else {
            throw new Exception('Viagem não encontrada no sistema');
        }
    }

    public function listarPorIdVeiculo($idVeiculo)
    {
        $viagens = $this->DAO->listarPorIdVeiculo($idVeiculo);
        return $viagens;
    }

    public function verificaPermissao($idTarefa){
        $tarefaControl = new TarefaControl(null);
        $idProjeto = $tarefaControl->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $resposta = $projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id']);
        return $resposta;
    }

    public function verificaDados($dados,$requisicao)
    {
        if ($requisicao == 'cadastro') {
            if (!isset($dados->dataIda) || !isset($dados->dataVolta) || !isset($dados->origem) || !isset($dados->destino) ||
                !isset($dados->passagem) || !isset($dados->justificativa) || !isset($dados->observacoes) || !isset($dados->dtEntradaHosp) ||
                !isset($dados->dtSaidaHosp) || !isset($dados->horaEntradaHosp) || !isset($dados->horaSaidaHosp) || !isset($dados->gastos) ||
                !isset($dados->veiculo) || !isset($dados->idTarefa) || !is_array($dados->gastos) || !isset($dados->tipo) || !isset($dados->tipoPassagem) ||
                !isset($dados->meta) || !isset($dados->fonte) || !isset($dados->atividade)) {
                return false;
            } else {
                foreach ($dados->gastos as $gasto) {
                    if (!isset($gasto->valor) || !isset($gasto->tipo)){
                        return false;
                    }
                }
            }
        }
        if ($requisicao == 'atualizacao') {
            if (!isset($dados->dataIda) || !isset($dados->dataVolta) || !isset($dados->origem) || !isset($dados->destino) ||
                !isset($dados->passagem) || !isset($dados->justificativa) || !isset($dados->observacoes) || !isset($dados->dtEntradaHosp) ||
                !isset($dados->dtSaidaHosp) || !isset($dados->horaEntradaHosp) || !isset($dados->horaSaidaHosp) || !isset($dados->veiculo) ||
                !isset($dados->tipo) || !isset($dados->tipoPassagem) || !isset($dados->meta) || !isset($dados->fonte) || !isset($dados->atividade)){
                return false;
            }
        }
        return true;
    }
}

