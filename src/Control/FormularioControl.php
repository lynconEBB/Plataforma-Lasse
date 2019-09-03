<?php

namespace Lasse\LPM\Control;


use Exception;

class FormularioControl extends CrudControl
{
    public function __construct($url)
    {
        $this->requisitor = UsuarioControl::autenticar();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        switch ($this->metodo) {
            case 'POST':
                // /api/formularios
                if (count($this->url) == 2) {
                    if (isset($_POST['nome']) && isset($_FILES['formulario'])) {
                        $this->cadastrar($_FILES['formulario'],$_POST['nome']);
                    } else {
                        throw new Exception("Paramentros insuficientes ou mal estruturados");
                    }
                }
        }
    }

    public function cadastrar($arquivo,$nome)
    {
        if (strpos('/',$nome) == false && strpos(' ',$nome) == false && is_string($arquivo['name'])) {
            $pastaUsuario = $_SERVER['DOCUMENT_ROOT']."/assets/files/".$this->requisitor['id'];
            $caminhoArquivoTemp = $arquivo['tmp_name'];
            $extensao = pathinfo($arquivo['name'],PATHINFO_EXTENSION);
            if ($extensao != 'odt' && $extensao != 'docx') {
                throw new Exception("Formato de arquivo não suportado");
            }
            $caminhoArquivoUpload = $pastaUsuario."/".$nome.".".$extensao;
            if (is_file($caminhoArquivoUpload)) {
                unlink($caminhoArquivoUpload);
            }
            putenv('PATH=/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin');
            putenv('HOME=' . $pastaUsuario);

            if (move_uploaded_file($caminhoArquivoTemp,$caminhoArquivoUpload)) {
                $comando = "soffice --headless --convert-to html:HTML --outdir ";
                $comando .= $pastaUsuario." ";
                $comando .= $caminhoArquivoUpload;

                if (exec($comando)) {
                    require $pastaUsuario.'/formViagem.html';
                } else {
                    echo 'errou feio';
                }

            } else {
                throw new Exception("");
            }
        } else {
            throw new Exception("Nome de arquivo invalido");
        }
    }
}