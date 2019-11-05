<?php


namespace Lasse\LPM\Model;

use DateTime;
use Lasse\LPM\Services\Formatacao;
use Lasse\LPM\Services\Validacao;

class FormularioModel
{
    private $id;
    private $nome;
    private $caminhoDocumento;
    private $usuario;
    private $compra;
    private $viagem;
    private $dataModificacao;

    public function __construct($nome,$usuario,$dataCriacao,$caminhoDocumento=null,$id=null,$viagem=null,$compra=null)
    {
        $this->setId($id);
        $this->setUsuario($usuario);
        $this->setDataModificacao($dataCriacao);
        $this->setNome($nome);
        $this->setCaminhoDocumento($caminhoDocumento);
        $this->setCompra($compra);
        $this->setViagem($viagem);
    }

    public function toArray()
    {
        $array = [
            "id" => $this->getId(),
            "nome" => $this->getNome(),
            "caminhoDocumento" => $this->getCaminhoDocumento(),
            "usuario" => $this->getUsuario()->toArray(),
        ];
        return $array;
    }

    public function getDataModificacao():DateTime
    {
        return $this->dataModificacao;
    }

    public function setDataModificacao($dataModificacao)
    {
        Validacao::validar("Data de Criação",$dataModificacao,"data");
        $this->dataModificacao = Formatacao::formataData($dataModificacao);
    }

    public function getCompra()
    {
        return $this->compra;
    }

    public function setCompra($compra)
    {
        $this->compra = $compra;
    }

    public function getViagem()
    {
        return $this->viagem;
    }

    public function setViagem($viagem)
    {
        $this->viagem = $viagem;
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
            $nomeFormatado = str_replace(" ","",$this->nome);
            $this->caminhoDocumento = "assets/files/{$this->getUsuario()->getId()}/{$nomeFormatado}.odt";
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
}
