<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\GastoDao;
use Lasse\LPM\Model\GastoModel;

class GastoControl extends CrudControl
{

    public function __construct($url){
        UsuarioControl::autenticar();
        $this->DAO = new GastoDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $requestBody = json_decode(file_get_contents('php://input'));
                    if (isset($requestBody->idViagem)) {
                        $this->cadastrar($requestBody,$requestBody->idViagem);
                        $this->respostaSucesso("Gasto Cadastrado com sucesso");
                    } else {
                        throw new Exception("Parametros faltando ou mal estruturados");
                    }
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

    public function cadastrar($dados,$idViagem){
        if (isset($dados->tipo) && isset($dados->valor)) {
            $gasto = new GastoModel($dados->valor,$dados->tipo);
            $this->DAO->cadastrar($gasto,$idViagem);
            $viagemControl = new ViagemControl(null);
            $viagemControl->atualizaTotal($idViagem);
        }else {
            throw new Exception("Parametros faltando ou mal estruturados no cadastramento de Gastos");
        }
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

    protected function atualizar(){
        $gasto = new GastoModel($_POST['valor'],$_POST['tipoGasto'],$_POST['id']);
        $this -> DAO -> atualizar($gasto);

        $viagemControl = new ViagemControl();
        $viagemControl->atualizaTotal($_POST['idViagem']);
    }
}
