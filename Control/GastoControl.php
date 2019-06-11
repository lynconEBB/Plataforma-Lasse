<?php

require_once '../Services/Autoload.php';

class GastoControl extends CrudControl
{

    public function __construct(){
        $this->DAO = new GastoDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 1:
                $this->cadastrar();
                header('Location:../View/GastoView.php');
                break;
            case 2:
                $this->excluir($_POST['id']);
                header('Location:../View/CondutorView.php');
                break;
            case 3:
                $this->atualizar();
                break;
        }
    }

    public function cadastrar(){
        $gasto = new GastoModel($_POST['valor'],$_POST['nome']);
        $this->DAO->cadastrar($gasto);
    }

    public function cadastrarGastos($gastos,$idViagem){
        $listaGastos = array();
        foreach ($gastos as $gast){
            $gasto = new GastoModel($gast['valor'],$gast['nome']);
            $listaGastos[] = $gasto;
        }

        $this->DAO->cadastrarGastos($listaGastos,$idViagem);
    }

    protected function excluir($id){
        $this -> DAO -> excluir($id);
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

        header('Location:../View/GastoView.php');

    }
}

new GastoControl();