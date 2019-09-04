<?php

namespace Lasse\LPM\Control;


use DOMDocument;
use DOMXPath;
use Exception;
use Lasse\LPM\Dao\FormularioDao;
use Lasse\LPM\Model\FormularioModel;

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
                            //$this->respostaSucesso("Formulario Cadastrado com sucesso",null,$this->requisitor);
                        } else {
                            throw new Exception("Apenas 1 arquivo é permitido");
                        }
                    } else {
                        throw new Exception("Parâmetros insuficientes ou mal estruturados");
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
        }
    }

    public function cadastrar($arquivo,$nome)
    {
        //if ($this->DAO->listarPorUsuarioNome($nome,$this->requisitor['id']) == false) {
            $caminhoArquivoTemp = $arquivo['tmp_name'];
            $extensao = pathinfo($arquivo['name'],PATHINFO_EXTENSION);
            $caminhoArquivoUpload = $this->pastaUsuario."/".$nome.".".$extensao;
            $caminhoArquivoHTML = $this->pastaUsuario."/".$nome.".html";

            $formulario = new FormularioModel($nome,$caminhoArquivoUpload,$caminhoArquivoHTML,null);

            if (move_uploaded_file($caminhoArquivoTemp,$formulario->getCaminhoDocumento())) {
                //$html = $this->converterParaHTML($formulario);
                $newHtml = "";
                $html = file_get_contents($caminhoArquivoHTML);
                $domDoc = new domDocument();
                $domDoc->loadHTML('<a href="http://foo.bar/">Click here</a>');
                $imgs = $domDoc->getElementsByTagName('a');
                var_dump($imgs);
                foreach ($domDoc->getElementsByTagName('a') as $item) {

                }
                //$this->DAO->cadastrar($formulario,$this->requisitor['id']);
            } else {
                if (is_file($formulario->getCaminhoDocumento())) {
                    unlink($formulario->getCaminhoDocumento());
                }
                throw new Exception("Não foi possível upar o arquivo");
            }
       // } else {
           // throw new Exception("Nome de Formulário já utilizado");
       // }

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

}