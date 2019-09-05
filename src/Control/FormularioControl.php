<?php

namespace Lasse\LPM\Control;


use Exception;
use Lasse\LPM\Dao\FormularioDao;
use Lasse\LPM\Model\FormularioModel;
use Lasse\LPM\Services\OdtManipulator;

class FormularioControl extends CrudControl
{
    private $pastaUsuario;

    public function __construct($url)
    {
        $this->DAO = new FormularioDao();
        $this->requisitor = UsuarioControl::autenticar();
        $this->pastaUsuario = $_SERVER['DOCUMENT_ROOT']."/assets/files/".$this->requisitor['id'];
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
                    $this->gerarRequisicaoViagem($this->url[3]);
                    //$this->respostaSucesso("Formulario de Requisicao de Viagem criado com sucesso",null,$this->requisitor);
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

                break;
        }
    }

    public function cadastrar($arquivo,$nome)
    {
        if ($this->DAO->listarPorUsuarioNome($nome,$this->requisitor['id']) == false) {
            $caminhoArquivoTemp = $arquivo['tmp_name'];
            $extensao = pathinfo($arquivo['name'],PATHINFO_EXTENSION);
            $caminhoArquivoUpload = $this->pastaUsuario."/".$nome.".".$extensao;
            $caminhoArquivoHTML = $this->pastaUsuario."/".$nome.".html";

            $formulario = new FormularioModel($nome,$caminhoArquivoUpload,$caminhoArquivoHTML,null);

            if (move_uploaded_file($caminhoArquivoTemp,$formulario->getCaminhoDocumento())) {
                $html = $this->converterParaHTML($formulario);
                $this->DAO->cadastrar($formulario,$this->requisitor['id']);
            } else {
                if (is_file($formulario->getCaminhoDocumento())) {
                    unlink($formulario->getCaminhoDocumento());
                }
                throw new Exception("Não foi possível upar o arquivo");
            }
        } else {
            throw new Exception("Nome de Formulário já utilizado");
        }

    }

    private function converterParaHTML(FormularioModel $formulario)
    {
        putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
        putenv('HOME=' . $this->pastaUsuario);
        $comando = "soffice --headless --convert-to html:HTML --outdir ";
        $comando .= $this->pastaUsuario." ";
        $comando .= $formulario->getCaminhoDocumento();

        if (exec($comando)) {
            return file_get_contents($formulario->getCaminhoHTML());
        } else {
            if (is_file($formulario->getCaminhoHTML())) {
                unlink($formulario->getCaminhoHTML());
            }
            throw new Exception("Erro durante conversão para exibição");
        }
    }

    public function excluir($id)
    {

    }

    public function gerarRequisicaoViagem($idViagem) {
        $file = $_SERVER['DOCUMENT_ROOT']."/requisicaoViagem.odt";



        /*$viagemControl = new ViagemControl(null);
        $viagem = $viagemControl->listarPorId($idViagem);
        if ($viagem->getViajante()->getId() ==  $this->requisitor['id']) {
            $arquivo = "/home/lasse/Lasse-Project-Manager/assets/files/default/requisicaoViagem.odt";
            //echo $arquivo;
            $pasta = $this->pastaUsuario."/tmp";
            $manipulator = new OdtManipulator($arquivo,$pasta);

        } else {
            throw new Exception("Você não possui permissão para gerar um formulário desta viagem");
        }*/
    }

}