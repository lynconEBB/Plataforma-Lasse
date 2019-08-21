<?php

namespace Lasse\LPM\Control;

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

                    break;
                case 'GET':
                    break;
                case 'PUT':
                    break;
                case 'DELETE':
                    break;
            }
        }
    }

    public function cadastrar($valor,$nome,$quantidade,$idCompra)
    {
        $item = new ItemModel($valor,$nome,$quantidade);
        $this->DAO->cadastrar($item,$idCompra);
        $compraControl = new CompraControl(null);
        $compraControl->atualizarTotal($idCompra);
    }

    protected function excluir($id)
    {
        $this -> DAO -> excluir($id);
        $compraControl = new CompraControl();
        $compraControl->atualizarTotal($_POST['idCompra']);
    }

    public function listar()
    {
        return $this -> DAO -> listar();
    }

    public function listarPorIdCompra($idCompra)
    {
        return $this -> DAO -> listarPorIdCompra($idCompra);
    }

    protected function atualizar()
    {
        $item = new ItemModel($_POST['valor'],$_POST['nome'],$_POST['qtd'],$_POST['id']);
        $this -> DAO -> atualizar($item);
        $compraControl = new CompraControl();
        $compraControl->atualizarTotal($_POST['idCompra']);
    }
}
