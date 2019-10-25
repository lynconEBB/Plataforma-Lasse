<?php

namespace Lasse\LPM\Control;

use InvalidArgumentException;
use Lasse\LPM\Dao\VeiculoDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Model\VeiculoModel;
use stdClass;
use UnexpectedValueException;

class VeiculoControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new VeiculoDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            $requisicaoEncontrada = false;
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/veiculos
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info);
                        $this->respostaSucesso("Veiculo cadastrado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/veiculos
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $veiculos = $this->listar();
                        if ($veiculos != false) {
                            $this->respostaSucesso("Listando todos veiculos",$veiculos,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum Veiculo Encontrado",null,$this->requisitor);
                        }

                    }
                    // /api/veiculos/{idVeiculo}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $veiculo = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listando veiculo de id: ".$this->url[2],$veiculo,$this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/veiculos/{idVeiculo}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Veiculo atualizado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/veiculos/{idVeiculo}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Veiculo excluido com sucesso",null,$this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function cadastrar($info){
        if (isset($info->nome) && isset($info->tipo) && isset($info->dtRetirada) && isset($info->horaRetirada) && isset($info->dtDevolucao) && isset($info->horaDevolucao) && isset($info->condutor) ) {
            $condControl = new CondutorControl(null);
            if ($info->condutor instanceof stdClass) {
                $condControl->cadastrar($info->condutor);
                $id = $condControl->DAO->pdo->lastInsertId();
                $condutor = $condControl->listarPorId($id);
            } else {
                $condutor = $condControl->listarPorId($info->condutor);
            }
            $veiculo = new VeiculoModel($info->nome,$info->tipo,$info->dtRetirada.' '.$info->horaRetirada,$info->dtDevolucao.' '.$info->horaDevolucao,$condutor);
            $this->DAO->cadastrar($veiculo);
        } else {
            throw new UnexpectedValueException("Parâmetros insuficientes ou mal estruturados");
        }
    }

    protected function excluir($id){
        $this->listarPorId($id);
        $viagemControl = new ViagemControl(null);
        if ($viagemControl->listarPorIdVeiculo($id) == false) {
            $this->DAO->excluir($id);
        } else {
            throw new InvalidArgumentException("Não foi possível excluir este veiculo pois já está em uso");
        }
    }

    public function listar(){
        $veiculos = $this->DAO->listar();
        return $veiculos;
    }

    protected function atualizar($info,$id){
        $this->listarPorId($id);
        if (isset($info->nome) && isset($info->tipo) && isset($info->dtRetirada) && isset($info->horaRetirada) && isset($info->dtDevolucao) && isset($info->horaDevolucao) && isset($info->condutor) ) {
            $viagemControl = new ViagemControl(null);
            if ($viagemControl->listarPorIdVeiculo($id) == false) {
                $condControl = new CondutorControl(null);
                if ($info->condutor instanceof stdClass) {
                    $condControl->cadastrar($info->condutor);
                    $idCondutor = $condControl->DAO->pdo->lastInsertId();
                    $condutor = $condControl->listarPorId($idCondutor);
                } else {
                    $condutor = $condControl->listarPorId($info->condutor);
                }
                $veiculo = new VeiculoModel($info->nome,$info->tipo,$info->dtRetirada.' '.$info->horaRetirada,$info->dtDevolucao.' '.$info->horaDevolucao,$condutor,$id);
                $this->DAO->atualizar($veiculo);
            } else {
                throw new InvalidArgumentException("Não é possível atualizar este veiculo pois já está em uso");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }
    }

    public function listarPorId($id){
        $veiculo = $this->DAO->listarPorId($id);
        if ($veiculo != false) {
            return $veiculo;
        } else {
            throw new NotFoundException("Veículo não encotrado");
        }
    }

    public function listarPorIdCondutor($idCondutor){
        $veiculos = $this->DAO->listarPorIdCondutor($idCondutor);
        return $veiculos;
    }
}
