<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\CompraDao;
use Lasse\LPM\Model\CompraModel;

class CompraControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new CompraDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            switch ($this->metodo){
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/compras/
                    if (count($this->url) == 2) {;
                        $this->cadastrar($info);
                        $this->respostaSucesso("Compra cadastrada com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/compras/
                    if (count($this->url) == 2) {
                        if ($this->requisitor['admin'] == "1") {
                            $compras = $this->listar();
                            if ($compras != false ) {
                                $this->respostaSucesso("Listando todas as compras cadastradas no sistema",$compras, $this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhuma compra encontrada no sistema",null,$this->requisitor);
                            }
                        } else {
                            throw new Exception("Você precisa ser administrador para acessar todas as compras");
                        }
                    }
                    // /api/compras/{idCompra}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $compra = $this->listarPorId($this->url[2]);
                        if ($compra->getComprador()->getId() == $this->requisitor['id'] || $this->requisitor['admin'] == "1") {
                            $compras = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando compra de id: {$this->url[2]}",$compras, $this->requisitor);
                        } else {
                            throw new Exception("Você não possui acesso aos detalhes desta compra");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/compras/{idCompra}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->atualizar($info->proposito,$this->url[2]);
                        $this->respostaSucesso("Compra atualizada com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/compras/{idCompra}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Compra excluida com sucesso",null,$this->requisitor);
                    }
                    break;
            }
        }
    }

    public function cadastrar($info)
    {
        if (isset($info->proposito) && isset($info->idTarefa) && isset($info->intens)) {
            if (is_array($info->itens) && $this->verificaPermissao($info->idTarefa)) {
                $usuarioControl = new UsuarioControl(null);
                $usuario = $usuarioControl->listarPorId($this->requisitor['id']);
                //Cadastra no banco de dados com Total = 0
                $compra = new CompraModel($info->proposito,null,null,null,$usuario);
                $this->DAO->cadastrar($compra,$info->idTarefa);
                //Pega id da Compra Inserida
                $idCompra = $this->DAO->pdo->lastInsertId();
                //Cadastra no banco todos os itens da Compra
                $itemControl = new ItemControl();
                foreach ($info->itens as $item) {
                    $itemControl->cadastrar($item->valor,$item->nome,$item->quantidade,$idCompra);
                }
            } else {
                throw new Exception("Requisição mal estruturada ou com valores inválidos");
            }
        } else {
            throw new Exception("Parametros insuficientes ou mal estruturados",400);
        }
    }

    protected function excluir($id)
    {
        $compra = $this->listarPorId($id);
        if ($compra != false) {
            if ($compra->getComprador()->getId() == $this->requisitor['id'] ) {
                $idTarefa = $this->DAO->descobreIdTarefa($id);
                $this->DAO->excluir($id);
                $tarefaControl = new TarefaControl(null);
                $tarefaControl->atualizaTotal($idTarefa);
            } else {
                throw new Exception("Usuário não possui permissão para excluir esta compra");
            }
        } else {
            throw new Exception("Compra não encontrada no sistema");
        }
    }

    public function atualizar($proposito,$id)
    {
        $compra = $this->listarPorId($id);
        if ($compra != false) {
            if ($compra->getComprador()->getId() == $this->requisitor['id']) {
                $compraNova = new CompraModel($proposito,null,null,$id,null);
                $this->DAO->atualizar($compraNova);
            } else {
                    throw new Exception("Usuário não possui permissão para Atualizar esta compra");
            }
        } else {
            throw new Exception("Compra não encontrada no sistema");
        }
    }

    public function listar()
    {
        $compras = $this->DAO->listar();
        return $compras;
    }

    public function listarPorId($id)
    {
        $idTarefa = $this->DAO->descobreIdTarefa($id);
        if (!is_null($idTarefa)) {
            if ($this->verificaPermissao($idTarefa)) {
                $compra = $this->DAO->listarPorId($id);
                return $compra;
            } else {
                throw new Exception("Permissão de acesso a Compra Negada");
            }
        } else {
            throw new Exception("Compra não encontrada no sistema");
        }
    }

    public function atualizarTotal($idCompra)
    {
        $compra = $this->DAO->listarPorId($idCompra);
        $this->DAO->atualizarTotal($compra);
        $idTarefa = $this->DAO->descobreIdTarefa($idCompra);
        $tarefaControl = new TarefaControl(null);
        $tarefaControl->atualizaTotal($idTarefa);
    }

    public function verificaPermissao($idTarefa)
    {
        $tarefaControl = new TarefaControl(null);
        $idProjeto = $tarefaControl->descobrirIdProjeto($idTarefa);
        $projetoControl = new ProjetoControl(null);
        $resposta = $projetoControl->procuraFuncionario($idProjeto,$this->requisitor['id']);
        return $resposta;
    }

    public function descobrirIdTarefa($idCompra)
    {
        $idTarefa = $this->DAO->descobreIdTarefa($idCompra);
        if ($idTarefa != false ) {
            return $idTarefa;
        } else {
            throw new Exception("Compra não encontrada no sistema");
        }
    }
}