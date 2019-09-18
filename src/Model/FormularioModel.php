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
    private $pastaFormulario;

    public function __construct( $nome,$usuario, $caminhoDocumento=null, $caminhoHTML=null,$id=null)
    {
        $this->setId($id);
        $this->setUsuario($usuario);
        $this->setNome($nome);
        $this->setPastaFormulario();
        $this->setCaminhoDocumento($caminhoDocumento);
        $this->setCaminhoHTML($caminhoHTML);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->getId(),
            "nome" => $this->getNome(),
            "caminhoDocumento" => $this->getCaminhoDocumento(),
            "caminhoHTML" => $this->getCaminhoHTML(),
            "usuario" => $this->getUsuario()->toArray(),
        ];
        return $array;
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
            $this->caminhoDocumento = $this->pastaFormulario."/{$this->nome}.odt";
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
            $this->caminhoHTML = $this->pastaFormulario."/{$this->nome}.html";
        }
    }

    public function getUsuario():UsuarioModel
    {
        return $this->usuario;
    }

    public function setUsuario(UsuarioModel $usuario)
    {
        $this->usuario = $usuario;
    }

    public function getPastaFormulario() :string
    {
        return $this->pastaFormulario;
    }

    public function setPastaFormulario()
    {
        $this->pastaFormulario = $_SERVER['DOCUMENT_ROOT']."/assets/files/{$this->usuario->getId()}/{$this->nome}";
    }





}