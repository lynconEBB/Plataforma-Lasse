<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\ItemDao;
use Lasse\LPM\Model\ItemModel;

class ItemControl extends CrudControl{

    public function __construct($url=null){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new ItemDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/itens
                    if (count($this->url) == 2) {;
                        $this->cadastrar($info->valor,$info->nome,$info->quantidade,$info->idCompra);
                        $this->respostaSucesso("Item cadastrado com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/itens
                    if (count($this->url) == 2) {;
                        $itens = $this->listar();
                        $this->respostaSucesso("Listando todos Itens",$itens, $this->requisitor);
                    }
                    // /api/itens/compra/{idCompra}
                    elseif (count($this->url) == 4 && $this->url[2] == "compra" && $this->url[3] == (int)$this->url[3] ) {;
                        $itens = $this->listarPorIdCompra($this->url[3]);
                        $this->respostaSucesso("Listando itens da compra",$itens, $this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/itens/{idItem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {;
                        $this->atualizar($info->valor,$info->nome,$info->quantidade,$this->url[2]);
                        $this->respostaSucesso("Item atualizado com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/itens/{idItem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Item excluido com sucesso",null, $this->requisitor);
                    }
                    break;
            }
        }
    }

    public function cadastrar($valor,$nome,$quantidade,$idCompra)
    {
        $compraControl = new CompraControl(null);
        $compra = $compraControl->listarPorId($idCompra);
        if ($compra != false) {
            if ($compra->getComprador()->getId() == $this->requisitor['id']) {
                $item = new ItemModel($valor,$nome,$quantidade);
                $this->DAO->cadastrar($item,$idCompra);
                $compraControl->atualizarTotal($idCompra);
            } else {
                throw new Exception("Permissão negada, você precisa ser dono desta compra pra cadastrar novos itens");
            }
        } else {
            throw new Exception("Compra não encontrada");
        }
    }

    protected function excluir($id)
    {
        $compraControl = new CompraControl(null);
        $idCompra = $this->DAO->descobrirIdCompra($id);
        if (!is_null($idCompra)) {
            $compra = $compraControl->listarPorId($idCompra);
            if ($compra->getComprador()->getId() == $this->requisitor['id']) {
                $this->DAO->excluir($id);
                $compraControl->atualizarTotal($idCompra);
            } else {
                throw new Exception("Você não possui permissão para excluir itens desta compra");
            }
        }else {
            throw new Exception("Item não encontrado");
        }
    }

    public function listar()
    {
        $itens =  $this -> DAO -> listar();
        return $itens;
    }

    public function listarPorIdCompra($idCompra)
    {
        $compraControl = new CompraControl(null);
        $idTarefa = $compraControl->descibrirIdTarefa($idCompra);
        if ($compraControl->verificaPermissao($idTarefa)) {
            $itens =  $this -> DAO -> listarPorIdCompra($idCompra);
            return $itens;
        } else {
            throw new Exception("Permissão negada para acessar os itens desta compra");
        }
    }

    protected function atualizar($valor,$nome,$quantidade,$id)
    {
        $compraControl = new CompraControl(null);
        $idCompra = $this->DAO->descobrirIdCompra($id);
        if (!is_null($idCompra)) {
            $compra = $compraControl->listarPorId($idCompra);
            if ($compra->getComprador()->getId() == $this->requisitor['id']) {
                $item = new ItemModel($valor,$nome,$quantidade,$id);
                $this -> DAO -> atualizar($item);
                $compraControl->atualizarTotal($idCompra);
            } else {
                throw new Exception("Você não possui permissão para alterar este item");
            }
        } else {
            throw new Exception("Item não encontrado no sistema");
        }
    }
}