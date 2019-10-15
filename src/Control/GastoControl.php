<?php

namespace Lasse\LPM\Control;


use Lasse\LPM\Dao\GastoDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Model\GastoModel;
use UnexpectedValueException;

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
            $requisicaoEncontrada = false;
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(file_get_contents('php://input'));
                    // /api/gastos
                    if (isset($info->idViagem))  {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info,$info->idViagem);
                        $this->respostaSucesso("Gasto Cadastrado com sucesso");
                    } else {
                        throw new UnexpectedValueException("Parametros faltando ou mal estruturados");
                    }
                    break;
                case 'GET':
                    // /api/gastos
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        if ($this->requisitor['admin'] == "1") {
                            $gastos = $this->listar();
                            if ($gastos != false) {
                                $this->respostaSucesso("Listando gastos",$gastos,$this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhum gasto encontrado",$this->requisitor);
                                http_response_code(201);
                            }
                        } else {
                            throw new PermissionException("Você precisa ser administrador para ter acesso a todos os gastos","Acessar todos os gastos");
                        }
                    }
                    // /api/gastos/{idGasto}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $gasto = $this->listarPorId($this->url[2]);
                        $idViagem = $this->DAO->descobrirIdViagem($this->url[2]);
                        $viagemControl = new ViagemControl(null);
                        $viagem = $viagemControl->listarPorId($idViagem);

                        if ($this->requisitor['id'] == $viagem->getViajante()->getId() || $this->requisitor['admin'] == "1") {
                            $this->respostaSucesso("Gasto excluido com sucesso",$gasto, $this->requisitor);
                        } else {
                            throw new PermissionException("Você não possui acesso aos detalhes deste gasto","Acessar gastos de viagem realizada por outra pessoa");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(file_get_contents('php://input'));
                    // /api/gastos/{idGasto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        if (isset($info->valor) && isset($info->tipo)) {
                            $this->atualizar($info,$this->url[2]);
                            $this->respostaSucesso("Gasto atualizado com sucesso",null, $this->requisitor);
                        } else {
                            throw new UnexpectedValueException("Parametros faltando ou mal estruturados");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/gastos/{idGasto}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Gasto excluido com sucesso",null, $this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
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
                throw new UnexpectedValueException("Parametros faltando ou mal estruturados no cadastramento de Gastos");
            }
        } else {
            throw new PermissionException("Você não possui permissão para cadastrar gastos nesta viagem","Cadastrar gasto em uma viagem realizada por outro usuário");
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
            throw new PermissionException("Você não possui permissão para excluir gastos desta viagem","Excluir gasto de viagem realizada por outro usuário");
        }
    }

    public function listar()
    {
        $gastos = $this->DAO->listar();
        return $gastos;
    }

    public function listarPorId($id)
    {
        $gasto = $this->DAO->listarPorId($id);
        if ($gasto != false) {
            return $gasto;
        } else {
            throw new NotFoundException("Gasto não encontrado");
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
            throw new PermissionException("Você não possui permissão para atualizar gastos desta viagem","Atualizar gasto de viagem realizada por outro usuário");
        }
    }
}
