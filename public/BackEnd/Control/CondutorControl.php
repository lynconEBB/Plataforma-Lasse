<?php

namespace Lasse\LPM\Control;


use InvalidArgumentException;
use Lasse\LPM\Dao\CondutorDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Model\CondutorModel;
use UnexpectedValueException;

class CondutorControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new CondutorDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            $requisicaoEncontrada = false;
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/condutores
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info);
                        $this->respostaSucesso("Condutor cadastrado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/condutores
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $condutores = $this->listar();
                        if ($condutores != false) {
                            $this->respostaSucesso("Listando condutores",$condutores,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Nenhum condutor encontrado",null,$this->requisitor);
                        }
                    }
                    // /api/condutores/{idCondutor}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $condutor = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listando condutor de id: ".$this->url[2],$condutor,$this->requisitor);
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/condutores/{idCondutor}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->atualizar($info,$this->url[2]);
                        $this->respostaSucesso("Condutor atualizado com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/condutores/{idCondutor}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Condutor excluido com sucesso",null,$this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function cadastrar($dados){
        if (isset($dados->nome) && isset($dados->cnh) && isset($dados->validadeCNH)) {
            $condutor = new CondutorModel($dados->nome,$dados->cnh,$dados->validadeCNH);
            $this->DAO->cadastrar($condutor);
        } else {
            throw new UnexpectedValueException("Paramentros Insuficentes ou mal estruturados");
        }
    }

    public function excluir($id){
        $this->listarPorId($id);
        $veiculoControl = new VeiculoControl(null);
        if ($veiculoControl->listarPorIdCondutor($id) == false) {
            $this->DAO->excluir($id);
        } else {
            throw new InvalidArgumentException("Não é possivel excluir este condutor pois já está em uso");
        }
    }

    public function listar(){
        $condutores = $this->DAO->listar();
        return $condutores;
    }

    public function listarPorId($id){
        $condutor = $this->DAO->listarPorId($id);
        if ($condutor != false) {
            return $condutor;
        } else {
            throw new NotFoundException("Condutor não encontrado no sistema");
        }
    }

    protected function atualizar($info,$id)
    {
        if (isset($info->nome) && isset($info->cnh) && isset($info->validadeCNH)) {
            $this->listarPorId($id);
            $veiculoControl = new VeiculoControl(null);
            if ($veiculoControl->listarPorIdCondutor($id) == false) {
                $condutor = new CondutorModel($info->nome,$info->cnh,$info->validadeCNH,$id);
                $this->DAO->atualizar($condutor);
            } else {
                throw new InvalidArgumentException("Não é possivel atualizar este condutor pois já está em uso");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }
    }
}
