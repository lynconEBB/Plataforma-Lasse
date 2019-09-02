<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\VeiculoDao;
use Lasse\LPM\Model\VeiculoModel;
use stdClass;

class VeiculoControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new VeiculoDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/veiculos
                    if (count($this->url) == 2) {
                        $this->cadastrar($info);
                        $this->respostaSucesso("Veiculo cadastrado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/veiculos
                    if (count($this->url) == 2) {
                        $veiculos = $this->listar();
                        $this->respostaSucesso("Listando todos veiculos",$veiculos,$this->requisitor);
                    }
                    // /api/veiculos/{idVeiculo}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $veiculo = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listando veiculo de id: ".$this->url[2],$veiculo,$this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/veiculos/{idVeiculo}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Veiculo atualizado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/veiculos/{idVeiculo}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2] ) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Veiculo excluido com sucesso",null,$this->requisitor);
                    }
                    break;
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
            throw new Exception("Parâmetros insuficientes ou mal estruturados");
        }
    }

    protected function excluir($id){
        $this->listarPorId($id);
        $viagemControl = new ViagemControl(null);
        if ($viagemControl->listarPorIdVeiculo($id) == false) {
            $this->DAO->excluir($id);
        } else {
            throw new Exception("Não foi possível excluir este veiculo pois já está em uso");
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
                throw new Exception("Não é possível atualizar este veiculo pois já está em uso");
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }
    }

    public function listarPorId($id){
        $veiculo = $this->DAO->listarPorId($id);
        if ($veiculo != false) {
            return $veiculo;
        } else {
            throw new Exception("Veículo não encotrado");
        }
    }

    public function listarPorIdCondutor($idCondutor){
        $veiculos = $this->DAO->listarPorIdCondutor($idCondutor);
        return $veiculos;
    }
}
