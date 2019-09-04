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

    public function __construct( $nome, $caminhoDocumento, $caminhoHTML,$id)
    {
        $this->setId($id);
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

    public function setNome($nome): void
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
        Validacao::validar('Documento Formulário',$caminhoDocumento,'documento');
        $this->caminhoDocumento = $caminhoDocumento;
    }

    public function getCaminhoHTML()
    {
        return $this->caminhoHTML;
    }

    public function setCaminhoHTML($caminhoHTML): void
    {
        $extensao = pathinfo($caminhoHTML,PATHINFO_EXTENSION);
        if ($extensao != 'html') {
            throw new Exception("O caminho para arquivo HTML invalido");
        }
        $this->caminhoHTML = $caminhoHTML;
    }
}