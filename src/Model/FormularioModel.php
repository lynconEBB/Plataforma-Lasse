<?php


namespace Lasse\LPM\Model;


use Exception;
use Lasse\LPM\Services\Validacao;

class FormularioModel
{
    private $id;
    private $nome;
    private $caminhoDocumento;
    private $caminhoHTML;
    private $usuario;

    public function __construct( $nome,$usuario, $caminhoDocumento=null, $caminhoHTML=null,$id=null)
    {
        $this->setId($id);
        $this->setUsuario($usuario);
        $this->setNome($nome);
        $this->setCaminhoDocumento($caminhoDocumento);
        $this->setCaminhoHTML($caminhoHTML);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        Validacao::validar('Id',$id,'nuloOUinteiro');
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        Validacao::validar('Nome do Formulário',$nome,'nomeArquivo');
        $this->nome = $nome;
    }

    public function getCaminhoDocumento()
    {
        return $this->caminhoDocumento;
    }

    public function setCaminhoDocumento($caminhoDocumento)
    {
        if (!is_null($caminhoDocumento)) {
            Validacao::validar('Documento Formulário',$caminhoDocumento,'documento');
            $this->caminhoDocumento = $caminhoDocumento;
        } else {
            $this->caminhoDocumento = $_SERVER['DOCUMENT_ROOT']."/assets/files/{$this->usuario->id}/{$this->nome}/{$this->nome}.odt";
        }
    }

    public function getCaminhoHTML()
    {
        return $this->caminhoHTML;
    }

    public function setCaminhoHTML($caminhoHTML)
    {
        if (!is_null($caminhoHTML)) {
            $extensao = pathinfo($caminhoHTML,PATHINFO_EXTENSION);
            if ($extensao != 'html') {
                throw new Exception("O caminho para arquivo HTML invalido");
            }
            $this->caminhoHTML = $caminhoHTML;
        } else {
            $this->caminhoHTML = $_SERVER['DOCUMENT_ROOT']."/assets/files/{$this->usuario->id}/{$this->nome}/{$this->nome}.html";
        }

    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario(UsuarioModel $usuario)
    {
        $this->usuario = $usuario;
    }



}