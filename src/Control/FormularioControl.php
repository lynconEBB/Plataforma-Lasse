<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\FormularioDao;
use Lasse\LPM\Model\FormularioModel;
use Lasse\LPM\Model\ProjetoModel;
use Lasse\LPM\Model\TarefaModel;
use Lasse\LPM\Model\ViagemModel;
use Lasse\LPM\Services\HtmlManipulator;
use Lasse\LPM\Services\OdtManipulator;

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
        switch ($this->metodo) {
            case 'POST':
                // /api/formularios
                if (count($this->url) == 2) {
                    if (isset($_POST['nome']) && isset($_FILES['formulario'])) {
                        if (is_string($_FILES['formulario']['name'])) {
                            $this->cadastrar($_FILES['formulario'],$_POST['nome']);
                            $this->respostaSucesso("Formulario Cadastrado com sucesso",null,$this->requisitor);
                        } else {
                            throw new Exception("Apenas 1 arquivo é permitido");
                        }
                    } else {
                        throw new Exception("Parâmetros insuficientes ou mal estruturados");
                    }
                }
                // /api/formularios/requisicaoViagem/{idViagem}
                elseif (count($this->url) == 4 && $this->url[2] == "requisicaoViagem" && $this->url[3] == (int)$this->url[3]) {
                    $viagemControl = new ViagemControl(null);
                    $viagem = $viagemControl->listarPorId($this->url[3]);
                    if ($this->requisitor['id'] == $viagem->getViajante()->getId()) {
                        $formulario = $this->gerarRequisicaoViagem($this->url[3]);
                        $this->respostaSucesso("Formulario de Requisicao de Viagem criado com sucesso",$formulario,$this->requisitor);
                    } else {
                        throw new Exception("Você não possui permissão para gerar o formulario desta viagem");
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
                // /api/formularios/{idFormulario}
                if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                    $formulario = $this->listarPorId($this->url[2]);
                    if ($formulario->getUsuario()->getId() ==  $this->requisitor['id'] || $this->requisitor['admin'] == "1") {
                        $this->respostaSucesso("Listando formulario",$formulario,$this->requisitor);
                    } else {
                        throw new Exception("Você não possui acesso a este formulario",401);
                    }
                }
                // /api/formularios/users/{idUsuario}
                elseif ($this->url[2] == "users" && $this->url[3] == (int)$this->url[3] && count($this->url) == 4) {
                    $formularios = $this->listarPorIdUsuario($this->url[3]);
                    if ($formularios) {
                        $this->respostaSucesso("Listando formulários",$formularios,$this->requisitor);
                    } else {
                        $this->respostaSucesso("Nenhum formulário encontrado!",null,$this->requisitor);
                    }
                }
                // /api/formularios/download/{idFormulário}
                elseif ($this->url[2] == "download" && $this->url[3] == (int)$this->url[3] && count($this->url) == 4) {
                    $formulario = $this->listarPorId($this->url[3]);
                    if ($formulario) {
                        if ($formulario->getUsuario()->getId() == $this->requisitor['id']) {
                            echo file_get_contents($formulario->getCaminhoDocumento());
                        } else {
                            throw new Exception("Você não possui permissão para fazer o download deste formulário",401);
                        }
                    } else {
                        throw new Exception("Formulário não encontrado!",400);
                    }
                }
                break;
        }
    }

    public function cadastrar($arquivo,$nome)
    {
        if ($this->DAO->listarPorUsuarioNome($nome,$this->requisitor['id']) == false) {
            if (!is_dir($this->pastaUsuario)){
                mkdir($this->pastaUsuario);
            }
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($this->requisitor['id']);

            $formulario = new FormularioModel($nome,$usuario);
            if (!is_dir($formulario->getPastaFormulario())) {
                mkdir($formulario->getPastaFormulario());
            }

            if (move_uploaded_file($arquivo['tmp_name'],$formulario->getCaminhoDocumento())) {
                $this->converterParaHTML($formulario);
                $this->DAO->cadastrar($formulario,null,null);

            } else {
                if (is_file($formulario->getCaminhoDocumento())) {
                    unlink($formulario->getCaminhoDocumento());
                }
                if (is_dir($formulario->getPastaFormulario())) {
                    rmdir($formulario->getPastaFormulario());
                }
                throw new Exception("Não foi possível upar o arquivo");
            }
        } else {
            throw new Exception("Nome de Formulário já utilizado");
        }
    }

    public function excluir($id)
    {
        $formulario = $this->listarPorId($id);
        $this->DAO->excluir($id);
        array_map('unlink', glob("{$formulario->getPastaFormulario()}/*.*"));
        rmdir($formulario->getPastaFormulario());
    }

    public function listarPorId($id)
    {
        $formulario = $this->DAO->listarPorId($id);
        if ($formulario != false) {
            return $formulario;
        } else {
            throw new Exception("Formulário não encontrado");
        }
    }

    public function listarPorIdUsuario($idUsuario)
    {
        $usuarioControl = new UsuarioControl(null);
        $usuarioControl->listarPorId($idUsuario);
        $formularios = $this->DAO->listarPorIdUsuario($idUsuario);
        return $formularios;
    }


    private function converterParaHTML(FormularioModel $formulario)
    {
        putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
        putenv('HOME='.$_SERVER["DOCUMENT_ROOT"]."/".$formulario->getPastaFormulario());
        $comando = "soffice --headless --convert-to html:HTML:EmbedImages --outdir ";
        $comando .= $_SERVER["DOCUMENT_ROOT"]."/".$formulario->getPastaFormulario()." ";
        $comando .= $_SERVER["DOCUMENT_ROOT"]."/".$formulario->getCaminhoDocumento();

        if (exec($comando)) {
            $htmlManipulator = new HtmlManipulator($formulario->getCaminhoHTML());
            $htmlManipulator->consertaHTML();
            $htmlManipulator->salvar();
        } else {
            throw new Exception("Erro durante conversão para exibição");
        }
    }

    public function gerarRequisicaoViagem($idViagem) {

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
        $formulario = new FormularioModel("requisicaoViagem".$viagem->getId(),$usuario);

        if ($this->DAO->listarPorUsuarioNome($formulario->getNome(),$this->requisitor['id']) == false) {
            if ($viagem->getViajante()->getId() ==  $this->requisitor['id']) {

                if (!is_dir($this->pastaUsuario)) {
                    mkdir($this->pastaUsuario);
                }
                if (!is_dir($formulario->getPastaFormulario())) {
                    mkdir($formulario->getPastaFormulario());
                }

                if (copy($caminhoOdtRequisicao,$formulario->getCaminhoDocumento())) {
                    $this->preencherCamposRequisicao($viagem,$formulario,$projeto,$tarefa);
                    $this->converterParaHTML($formulario);
                    $this->DAO->cadastrar($formulario,$idViagem,null);
                    $formulario->setId($this->DAO->pdo->lastInsertId());
                    return $formulario;
                }
                else {
                    if (is_dir($formulario->getPastaFormulario())) {
                        rmdir($formulario->getPastaFormulario());
                    }
                    throw new Exception("Erro ao tentar criar arquivo");
                }
            } else {
                throw new Exception("Você não possui permissão para gerar um formulário desta viagem");
            }
        } else {
            throw new Exception("Formulário já criado");
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

}
