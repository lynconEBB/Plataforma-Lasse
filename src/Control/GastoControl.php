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
        switch ($this->metodo){
            case 'POST':
                $requestBody = json_decode(file_get_contents('php://input'));
                if (isset($requestBody->tipo) && isset($requestBody->valor) && isset($requestBody->idViagem)) {
                    $this->cadastrar($requestBody->tipo,$requestBody->valor,$requestBody->idViagem);
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

    public function cadastrar($tipo,$valor,$idViagem){
        $gasto = new GastoModel($valor,$tipo);
        $this->DAO->cadastrar($gasto,$idViagem);
        $viagemControl = new ViagemControl(null);
        $viagemControl->atualizaTotal($idViagem);
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
