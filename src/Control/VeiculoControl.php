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
                        if (isset($info->nome) && isset($info->tipo) && isset($info->dtRetirada) && isset($info->horaRetirada) && isset($info->dtDevolucao) && isset($info->horaDevolucao) && isset($info->condutor) && isset($info->id) ) {
                            $this->atualizar($info->nome,$info->tipo,$info->dtRetirada,$info->horaRetirada,$info->dtDevolucao,$info->horaDevolucao,$info->condutor,$info->id);
                            $this->respostaSucesso("Veiculo atualizado com sucesso",null,$this->requisitor);
                        } else {
                            throw new Exception("Parâmetros insuficientes ou mal estruturados");
                        }
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
        $this->DAO->excluir($id);
    }

    public function listar(){
        $veiculos = $this->DAO->listar();
        return $veiculos;
    }

    protected function atualizar($nome,$tipo,$dtRetirada,$horaRetirada,$dtDevolucao,$horaDevolucao,$condutor,$id){
        $this->listarPorId($id);
        $condControl = new CondutorControl(null);
        if ($condutor instanceof stdClass) {
            $condControl->cadastrar($condutor->nome,$condutor->cnh,$condutor->validadeCNH);
            $idCondutor = $condControl->DAO->pdo->lastInsertId();
            $condutor = $condControl->listarPorId($idCondutor);
        } else {
            $condutor = $condControl->listarPorId($condutor);
        }
        $veiculo = new VeiculoModel($nome,$tipo,$dtRetirada.' '.$horaRetirada,$dtDevolucao.' '.$horaDevolucao,$condutor,$id);
        $this->DAO->atualizar($veiculo);
    }

    public function listarPorId($id){
        $veiculo = $this->DAO->listarPorId($id);
        if ($veiculo != false) {
            return $veiculo;
        } else {
            throw new Exception("Veículo não encotrado");
        }
    }
}
