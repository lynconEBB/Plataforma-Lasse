<?php

namespace Lasse\LPM\Control;


use Lasse\LPM\Dao\ItemDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
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
            $requisicaoEncontrada = false;
            switch ($this->metodo) {
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/itens
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info->valor,$info->nome,$info->quantidade,$info->idCompra);
                        $this->respostaSucesso("Item cadastrado com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/itens
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $itens = $this->listar();
                        $this->respostaSucesso("Listando todos Itens",$itens, $this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/itens/{idItem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $this->atualizar($info->valor,$info->nome,$info->quantidade,$this->url[2]);
                        $this->respostaSucesso("Item atualizado com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/itens/{idItem}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Item excluido com sucesso",null, $this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function cadastrar($valor,$nome,$quantidade,$idCompra)
    {
        $compraControl = new CompraControl(null);
        $compra = $compraControl->listarPorId($idCompra);

        if ($compra->getComprador()->getId() == $this->requisitor['id']) {
            $item = new ItemModel($valor,$nome,$quantidade);
            $this->DAO->cadastrar($item,$idCompra);
            $compraControl->atualizarTotal($idCompra);
        } else {
            throw new PermissionException("Permissão negada, você precisa ser dono desta compra pra cadastrar novos itens",
                "Cadastrar itens em compra feita por outro usuário");
        }

    }

    protected function excluir($id)
    {
        $compraControl = new CompraControl(null);
        $idCompra = $this->DAO->descobrirIdCompra($id);
        $compra = $compraControl->listarPorId($idCompra);

        if ($compra->getComprador()->getId() == $this->requisitor['id']) {
            $this->DAO->excluir($id);
            $compraControl->atualizarTotal($idCompra);
        } else {
            throw new PermissionException("Você não possui permissão para excluir itens desta compra","Excluir itens de uma compra feita por outro usuário");
        }
    }

    public function listar()
    {
        $itens =  $this -> DAO -> listar();
        return $itens;
    }

    protected function atualizar($valor,$nome,$quantidade,$id)
    {
        $compraControl = new CompraControl(null);
        $idCompra = $this->DAO->descobrirIdCompra($id);

        $compra = $compraControl->listarPorId($idCompra);
        if ($compra->getComprador()->getId() == $this->requisitor['id']) {
            $item = new ItemModel($valor,$nome,$quantidade,$id);
            $this -> DAO -> atualizar($item);
            $compraControl->atualizarTotal($idCompra);
        } else {
            throw new PermissionException("Você não possui permissão para alterar este item","Alterar Itens de uma compra feita por outro usuario");
        }
    }
}
