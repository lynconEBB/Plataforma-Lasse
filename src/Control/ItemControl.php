<?php

namespace Lasse\LPM\Control;

use Lasse\LPM\Dao\ItemDao;
use Lasse\LPM\Model\ItemModel;

class ItemControl extends CrudControl{

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new ItemDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarItem':
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'excluirItem':
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'alterarItem':
                $this->atualizar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
        }
    }

    protected function cadastrar()
    {
        $item = new ItemModel($_POST['valor'],$_POST['nome'],$_POST['qtd']);
        $this->DAO->cadastrar($item,$_POST['idCompra']);
        $compraControl = new CompraControl();
        $compraControl->atualizarTotal($_POST['idCompra']);
    }

    public function cadastrarPorArray(array $itens, int $idCompra)
    {
        foreach ($itens as $item){
            $ite = new ItemModel($item['valor'],$item['nome'],$item['qtd']);
            $this->DAO->cadastrar($ite,$idCompra);
        }
    }

    protected function excluir(int $id)
    {
        $this -> DAO -> excluir($id);
        $compraControl = new CompraControl();
        $compraControl->atualizarTotal($_POST['idCompra']);
    }

    public function excluirPorIdCompra($id)
    {
        $this -> DAO -> excluirPorIdCompra($id);
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

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
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
}
