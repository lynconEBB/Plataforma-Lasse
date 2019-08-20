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
        switch ($this->metodo){
            case 'listaItens':
                $compraControl = new CompraControl();
                $compras = $compraControl->listar();
                require '../View/telaItensGerais.php';
                break;
            case 'listaItensCompra':
                $itens = $this->listarPorIdCompra($_GET['idCompra']);
                require '../View/telaItensCompra.php';
                break;
        }
    }

    public function cadastrar($valor,$nome,$quantidade,$idCompra)
    {
        $item = new ItemModel($valor,$nome,$quantidade);
        $this->DAO->cadastrar($item,$idCompra);
        $compraControl = new CompraControl(null);
        $compraControl->atualizarTotal($idCompra);
    }

    protected function excluir(int $id)
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
