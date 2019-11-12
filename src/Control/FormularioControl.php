<?php

namespace Lasse\LPM\Control;

use Exception;
use InvalidArgumentException;
use Lasse\LPM\Dao\FormularioDao;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Model\CompraModel;
use Lasse\LPM\Model\FormularioModel;
use Lasse\LPM\Model\ProjetoModel;
use Lasse\LPM\Model\TarefaModel;
use Lasse\LPM\Model\ViagemModel;
use Lasse\LPM\Services\OdtManipulator;
use UnexpectedValueException;

class FormularioControl extends CrudControl
{
    private $pastaUsuario;
    private $gastosPadroes = [
        "Aluguel de veículos (locado fora de Foz)",
        "Combustível",
        "Estacionamento",
        "Passagens rodoviárias (metrô/ônibus)",
        "Passagens rodoviárias internacionais",
        "Pedágio",
        "Seguro internacional (obrigatório)",
        "Táxi",
    ];

    public function __construct($url)
    {
        $this->DAO = new FormularioDao();
        $this->requisitor = UsuarioControl::autenticar();
        $this->pastaUsuario = "assets/files/".$this->requisitor['id'];
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo) {
                case 'POST':
                    // /api/formularios/viagem/{idViagem}
                    if (count($this->url) == 4 && $this->url[2] == "viagem" && $this->url[3] == (int)$this->url[3]) {
                        $viagemControl = new ViagemControl(null);
                        $viagem = $viagemControl->listarPorId($this->url[3]);
                        if ($this->requisitor['id'] == $viagem->getViajante()->getId()) {
                            $formulario = $this->cadastrarFormularioViagem($this->url[3]);
                            $this->respostaSucesso("Formulário de Requisicao de Viagem cadastrado com sucesso",$formulario,$this->requisitor);
                        } else {
                            throw new PermissionException("Você não possui permissão para gerar o formulario desta viagem");
                        }
                    }
                    // /api/formularios/compra/{idCompra}
                    elseif (count($this->url) == 4 && $this->url[2] == "compra" && $this->url[3] == (int)$this->url[3]) {
                        $compraControl = new CompraControl(null);
                        $compra = $compraControl->listarPorId($this->url[3]);
                        if ($this->requisitor['id'] == $compra->getComprador()->getId()) {
                            $formulario = $this->cadastrarFormularioCompra($compra);
                            $this->respostaSucesso("Formulário de Aquisição de Materiais cadastrado com sucesso",$formulario,$this->requisitor);
                        } else {
                            throw new PermissionException("Você não possui permissão para gerar o formulario desta compra");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/formularios/{idFormulario}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Excluido com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/formularios/users/{idUsuario}
                    if ($this->url[2] == "users" && $this->url[3] == (int)$this->url[3] && count($this->url) == 4) {
                        if ($this->requisitor['id'] == $this->url[3] || $this->requisitor['admin'] == "1") {
                            $formularios = $this->listarPorIdUsuario($this->url[3]);
                            if ($formularios) {
                                $this->respostaSucesso("Listando formulários",$formularios,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum formulário encontrado!",null,$this->requisitor);
                                http_response_code(202);
                            }
                        } else {
                            throw new PermissionException("Você não possui permissão para acessar os formulários deste usuário","Acessar formulários de outro usuário");
                        }
                    }
                    // /api/formularios/viagem/{idViagem}
                    elseif ($this->url[2] == "viagem" && $this->url[3] == (int)$this->url[3] && count($this->url) == 4) {
                        $formularios = $this->listarPorIdViagem($this->url[3]);
                        if ($formularios) {
                            $this->respostaSucesso("Listando formulários",$formularios,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum formulário encontrado!",null,$this->requisitor);
                            http_response_code(202);
                        }
                    }
                    // /api/formularios/viagem/{idViagem}
                    elseif ($this->url[2] == "compra" && $this->url[3] == (int)$this->url[3] && count($this->url) == 4) {
                        $formularios = $this->listarPorIdCompra($this->url[3]);
                        if ($formularios) {
                            $this->respostaSucesso("Listando formulários",$formularios,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum formulário encontrado!",null,$this->requisitor);
                            http_response_code(202);
                        }
                    }
                    // /api/formularios/download/{idFormulario}
                    elseif (count($this->url) == 4 && $this->url[2] == "download" && $this->url[3] == (int)$this->url[3]) {
                        $formulario = $this->listarPorId($this->url[3]);
                        if ($formulario->getUsuario()->getId() == $this->requisitor["id"]) {
                            header("Content-Type: application/vnd.oasis.opendocument.text");
                            header("Content-Transfer-Encoding: Binary");
                            header("Content-disposition: attachment; filename='".basename($formulario->getCaminhoDocumento())."'");
                            echo readfile($formulario->getCaminhoDocumento());
                        } else {
                            throw new PermissionException("Você não possui permissão para fazer o download deste formulario","Fazer download de um formulário de outro usuário");
                        }
                    }
                    break;
                case "PUT":
                    // /api/formularios/{idFormulario}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $formulario = $this->listarPorId($this->url[2]);
                        if ($formulario->getUsuario()->getId() == $this->requisitor['id'] ) {

                            $this->excluir($formulario->getId());
                            if ($formulario->getCompra() != null) {
                                $formulario = $this->cadastrarFormularioCompra($formulario->getCompra());
                            } else {
                                $formulario = $this->cadastrarFormularioViagem($formulario->getViagem()->getId());
                            }
                            $this->respostaSucesso("Dados Atualizados no formulário com sucesso",$formulario,$this->requisitor);
                        } else {
                            throw new PermissionException("Você não possui permissão para atualizar este formulário","Atualizar formulário de outro usuário");
                        }
                    }
                    break;
            }
        }
    }

    public function excluir($id)
    {
        $formulario = $this->listarPorId($id);
        $this->DAO->excluir($id);
        unlink($formulario->getCaminhoDocumento());
    }

    public function listarPorId($id):FormularioModel
    {
        $formulario = $this->DAO->listarPorId($id);
        if ($formulario != false) {
            return $formulario;
        } else {
            throw new UnexpectedValueException("Formulário não encontrado");
        }
    }

    public function listarPorIdViagem($idViagem)
    {
        $viagemControl = new ViagemControl(null);
        $viagemControl->listarPorId($idViagem);
        $formulario = $this->DAO->listarPorIdViagem($idViagem);
        return $formulario;
    }

    public function listarPorIdCompra($idCompra)
    {
        $compraControl = new CompraControl(null);
        $compraControl->listarPorId($idCompra);
        $formulario = $this->DAO->listarPorIdCompra($idCompra);
        return $formulario;
    }

    public function listarPorIdUsuario($idUsuario)
    {
        $usuarioControl = new UsuarioControl(null);
        $usuarioControl->listarPorId($idUsuario);
        $formularios = $this->DAO->listarPorIdUsuario($idUsuario);
        return $formularios;
    }

    public function cadastrarFormularioViagem($idViagem) {
        $viagemControl = new ViagemControl(null);
        $viagem = $viagemControl->listarPorId($idViagem);
        $idTarefa = $viagemControl->DAO->descobrirIdTarefa($idViagem);
        $tarefaControl = new TarefaControl(null);
        $tarefa = $tarefaControl->listarPorId($idTarefa);
        $idProjeto = $tarefaControl->DAO->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $projeto = $projetoControl->listarPorId($idProjeto);
        $usuarioControl = new UsuarioControl(null);
        $usuario = $usuarioControl->listarPorId($this->requisitor['id']);

        $caminhoOdtRequisicao = "assets/files/default/requisicaoViagem.odt";
        $formulario = new FormularioModel("Requisição de Viagem {$viagem->getId()}",$usuario,date("d/m/Y"),null,null,$viagem);

        if ($this->DAO->listarPorUsuarioNome($formulario->getNome(),$this->requisitor['id']) == false) {
            if ($viagem->getViajante()->getId() ==  $this->requisitor['id']) {

                if (!is_dir($this->pastaUsuario)) {
                    mkdir($this->pastaUsuario);
                }

                if (copy($caminhoOdtRequisicao,$formulario->getCaminhoDocumento())) {
                    $this->preencherCamposRequisicao($viagem,$formulario,$projeto,$tarefa);
                    $this->DAO->cadastrar($formulario);
                    $formulario->setId($this->DAO->pdo->lastInsertId());
                    return $formulario;
                }
                else {
                    throw new Exception("Erro ao tentar criar arquivo");
                }
            } else {
                throw new PermissionException("Você não possui permissão para gerar um formulário desta viagem");
            }
        } else {
            throw new InvalidArgumentException("Formulário já criado");
        }
    }

    private function preencherCamposRequisicao(ViagemModel $viagem,FormularioModel $formulario,ProjetoModel $projeto,TarefaModel $tarefa)
    {
        $odtManipulator = new OdtManipulator($formulario->getCaminhoDocumento());
        $odtManipulator->setCampo("nome",$viagem->getViajante()->getNomeCompleto());
        $odtManipulator->setCampo("cpf",$viagem->getViajante()->getCpf());
        $odtManipulator->setCampo("rg",$viagem->getViajante()->getRg());
        $odtManipulator->setCampo("dtNasc",$viagem->getViajante()->getDtNascimento()->format("d/m/Y"));
        $odtManipulator->setCampo("origem",$viagem->getOrigem());
        $odtManipulator->setCampo("destino",$viagem->getDestino());
        $odtManipulator->setCampo("dtIda",$viagem->getDtIda()->format("d/m/Y"));
        $odtManipulator->setCampo("dtVolta",$viagem->getDtVolta()->format("d/m/Y"));
        $odtManipulator->setCampo("justificativa",$viagem->getJustificativa());
        $odtManipulator->setCampo("observacoes",$viagem->getObservacoes());
        $odtManipulator->setCampo("passagem",$viagem->getPassagem());
        $odtManipulator->setCampo("condutor",$viagem->getVeiculo()->getCondutor()->getNome());
        $odtManipulator->setCampo("cnh",$viagem->getVeiculo()->getCondutor()->getCnh());
        $odtManipulator->setCampo("validadeCNH",$viagem->getVeiculo()->getCondutor()->getValidadeCNH()->format("d/m/Y"));
        $odtManipulator->setCampo("dtEntradaHosp",$viagem->getEntradaHosp()->format("d/m/Y"));
        $odtManipulator->setCampo("dtSaidaHosp",$viagem->getSaidaHosp()->format("d/m/Y"));
        $odtManipulator->setCampo("horaEntradaHosp",$viagem->getEntradaHosp()->format("h:i"));
        $odtManipulator->setCampo("horaSaidaHosp",$viagem->getSaidaHosp()->format("h:i"));
        $odtManipulator->setCampo("retiradaVeiculo",$viagem->getVeiculo()->getRetirada()->format("d/m/Y h:i"));
        $odtManipulator->setCampo("devolucaoVeiculo",$viagem->getVeiculo()->getDevolucao()->format("d/m/Y h:i"));
        $odtManipulator->setCampo("projeto",$projeto->getNome());
        $odtManipulator->setCampo("centroCusto",$projeto->getCentroCusto());
        $odtManipulator->setCampo("fonte",$viagem->getFonte());
        $odtManipulator->setCampo("tarefa",$tarefa->getNome());
        $odtManipulator->setCampo("atividade",$viagem->getAtividade());
        $odtManipulator->setCampo("veiculo",$viagem->getVeiculo()->getNome());
        $odtManipulator->setCheckBox($viagem->getViajante()->getAtuacao(),["col"=>"Colaborador","bol"=>"Bolsista/Voluntário","ter"=>"Terceiros"]);
        $odtManipulator->setCheckBox($viagem->getTipo(),["trab"=>"Viagem a trabalho","eve"=>"Evento/Congresso","trei" => "Viagem treinamento/aprimoramento"]);
        $odtManipulator->setCheckBox($viagem->getTipoPassagem(),["anf" => "Aérea nacional (com franquia de bagagem)","an" => "Aérea nacional (sem franquia de bagagem)", "ai" => "Aérea internacional", "tn" => "Terrestre nacional","ti" => "Terrestre internacional"]);

        $odtManipulator->setCheckBox($viagem->getVeiculo()->getTipo(),["loc" => "Veículo locado","par"=> "Transporte com parceiros/terceiros","pti"=> "Veículo Fundação PTI-BR"]);
        $outros = "0.00";
        foreach ($viagem->getGastos() as $gasto) {
            if (in_array($gasto->getTipo(),$this->gastosPadroes)) {
                $pos = array_search($gasto->getTipo(),$this->gastosPadroes);
                unset($this->gastosPadroes[$pos]);
                $odtManipulator->setCampo($gasto->getTipo(),$gasto->getValor());
            } else {
                $outros += $gasto->getValor();
            }
        }
        $odtManipulator->setCampo("outros",$outros);
        foreach ($this->gastosPadroes as $gasto) {
            $odtManipulator->setCampo($gasto,"0,00");
        }
        $odtManipulator->salvar();
    }

    public function cadastrarFormularioCompra(CompraModel $compra) {

        $compraControl = new CompraControl(null);
        $idTarefa = $compraControl->DAO->descobreIdTarefa($compra->getId());
        $tarefaControl = new TarefaControl(null);
        $tarefa = $tarefaControl->listarPorId($idTarefa);
        $idProjeto = $tarefaControl->DAO->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $projeto = $projetoControl->listarPorId($idProjeto);

        $caminhoOdtRequisicao = "assets/files/default/aquisicaoMateriais.odt";
        $formulario = new FormularioModel("Aquisição de Materiais {$compra->getId()}",$compra->getComprador(),date("d/m/Y"),null,null,null,$compra);

        if ($this->DAO->listarPorUsuarioNome($formulario->getNome(),$this->requisitor['id']) == false) {
            if ($compra->getComprador()->getId() ==  $this->requisitor['id']) {

                if (!is_dir($this->pastaUsuario)) {
                    mkdir($this->pastaUsuario);
                }

                if (copy($caminhoOdtRequisicao,$formulario->getCaminhoDocumento())) {
                    $this->preencherCamposCompra($compra,$formulario,$projeto,$tarefa);
                    $this->DAO->cadastrar($formulario);
                    $formulario->setId($this->DAO->pdo->lastInsertId());
                    return $formulario;
                }
                else {
                    throw new Exception("Erro ao tentar criar arquivo");
                }
            } else {
                throw new PermissionException("Você não possui permissão para gerar um formulário desta compra");
            }
        } else {
            throw new InvalidArgumentException("Formulário já criado");
        }
    }

    public function preencherCamposCompra(CompraModel $compra,FormularioModel $formulario,ProjetoModel $projeto,TarefaModel $tarefa) {
        $odtManipulator = new OdtManipulator($formulario->getCaminhoDocumento());
        $odtManipulator->setCampo("proposito",$compra->getProposito());
        $odtManipulator->setCampo("centroCusto",$projeto->getCentroCusto());
        $odtManipulator->setCampo("fonte",$compra->getFonteRecurso());
        $odtManipulator->setCampo("natOrcamentaria",$compra->getNaturezaOrcamentaria());
        $odtManipulator->setCampo("projeto",$projeto->getNome());
        $odtManipulator->setCampo("tarefa",$tarefa->getNome());
        $odtManipulator->salvar();
    }
}
