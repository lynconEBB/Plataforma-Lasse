<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\GastoDao;
use Lasse\LPM\Model\GastoModel;

class GastoControl extends CrudControl
{

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new GastoDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(file_get_contents('php://input'));
                    // /api/gastos
                    if (isset($info->idViagem))  {
                        $this->cadastrar($info,$info->idViagem);
                        $this->respostaSucesso("Gasto Cadastrado com sucesso");
                    } else {
                        throw new Exception("Parametros faltando ou mal estruturados");
                    }
                    break;
                case 'GET':
                    // /api/gastos
                    if (count($this->url) == 2) {
                        $gastos = $this->listar();
                    }
                    // /api/gastos/{idGasto}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $gasto = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Gasto excluido com sucesso",$gasto, $this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(file_get_contents('php://input'));
                    // /api/gastos/{idGasto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        if (isset($info->valor) && isset($info->tipo)) {
                            $this->atualizar($info,$this->url[2]);
                            $this->respostaSucesso("Gasto atualizado com sucesso",null, $this->requisitor);
                        } else {
                            throw new Exception("Parametros faltando ou mal estruturados");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/gastos/{idGasto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Gasto excluido com sucesso",null, $this->requisitor);
                    }
                    break;
            }
        }
    }

    public function cadastrar($dados,$idViagem){
        $viagemControl = new ViagemControl(null);
        $viagem = $viagemControl->listarPorId($idViagem);
        if ($viagem->getViajante()->getId() == $this->requisitor['id']) {
            if (isset($dados->tipo) && isset($dados->valor)) {
                $gasto = new GastoModel($dados->valor,$dados->tipo);
                $this->DAO->cadastrar($gasto,$idViagem);
                $viagemControl->atualizaTotal($idViagem);
            }else {
                throw new Exception("Parametros faltando ou mal estruturados no cadastramento de Gastos");
            }
        } else {
            throw new Exception("Você não possui permissão para cadastrar gastos nesta viagem");
        }
    }

    protected function excluir($id)
    {
        $idViagem = $this->DAO->descobrirIdViagem($id);
        $viagemControl = new ViagemControl(null);
        $viagem = $viagemControl->listarPorId($idViagem);
        if ($viagem->getViajante()->getId() == $this->requisitor['id']) {
            $this -> DAO -> excluir($id);
            $viagemControl->atualizaTotal($idViagem);
        } else {
            throw new Exception("Você não possui permissão para excluir gastos desta viagem");
        }
    }

    public function listar()
    {
        $gastos = $this->DAO->listar();
        if ($gastos != false) {
            $this->respostaSucesso("Listando gastos",$gastos,$this->requisitor);
        } else {
            $this->respostaSucesso("Nenhum gasto encontrado",$this->requisitor);
            http_response_code(201);
        }
    }

    public function listarPorId($id)
    {
        $gasto = $this->DAO->listarPorId($id);
        if ($gasto != false) {
            return $gasto;
        } else {
            throw new Exception("Gasto não encontrado");
        }
    }

    protected function atualizar($dados,$id)
    {
        $idViagem = $this->DAO->descobrirIdViagem($id);
        $viagemControl = new ViagemControl(null);
        $viagem = $viagemControl->listarPorId($idViagem);

        if ($viagem->getViajante()->getId() == $this->requisitor['id']) {
            $gasto = new GastoModel($dados->valor,$dados->tipo,$id);
            $this->DAO->atualizar($gasto);

            $viagemControl = new ViagemControl(null);
            $viagemControl->atualizaTotal($idViagem);
        } else {
            throw new Exception("Você não possui permissão para excluir gastos desta viagem");
        }
    }
}
