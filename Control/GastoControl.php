<?php

class GastoControl extends CrudControl
{

    public function __construct(){
        UsuarioControl::verificar();
        $this->DAO = new GastoDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarGasto':
                $this->cadastrar();
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'excluirGasto':
                $this->excluir($_POST['id']);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                break;
            case 'alterarGasto':
                $this->atualizar();
                break;
        }
    }

    public function cadastrar(){
        $gasto = new GastoModel($_POST['valor'],$_POST['tipoGasto']);
        $this->DAO->cadastrar($gasto,$_POST['idViagem']);
        $viagemControl = new ViagemControl();
        $viagemControl->atualizaTotal($_POST['idViagem']);
    }

    public function cadastrarGastos($gastos,$idViagem){
        $listaGastos = array();
        foreach ($gastos as $gast){
            $gasto = new GastoModel($gast['valor'],$gast['nome']);
            $listaGastos[] = $gasto;
        }

        $this->DAO->cadastrarGastos($listaGastos,$idViagem);
        return $listaGastos;
    }

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);
        $viagemControl = new ViagemControl();
        $viagemControl->atualizaTotal($_POST['idViagem']);
    }

    public function excluirPorIdViagem($id){
        $this -> DAO -> excluirPorIdViagem($id);
    }

    public function listar(){
        return $this -> DAO -> listar();
    }

    public function listarPorIdViagem($id){
        return $this -> DAO -> listarPorIdViagem($id);
    }


    protected function atualizar(){
        $gasto = new GastoModel($_POST['valor'],$_POST['tipoGasto'],$_POST['id']);
        $this -> DAO -> atualizar($gasto);

        $viagemControl = new ViagemControl();
        $viagemControl->atualizaTotal($_POST['idViagem']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'listaGastosGeral':
                $viagemControl = new ViagemControl();
                $viagens = $viagemControl->listar();
                require '../View/listaTodosGastos.php';
                break;
            case 'listaGastosViagem':
                $gastos = $this->listarPorIdViagem($_GET['idViagem']);
                require '../View/listaGastosViagem.php';
                break;
        }
    }
}
