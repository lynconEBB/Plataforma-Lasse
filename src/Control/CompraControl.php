<?php

namespace Lasse\LPM\Control;


use Lasse\LPM\Dao\CompraDao;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Model\CompraModel;
use UnexpectedValueException;

class CompraControl extends CrudControl {

    public function __construct($url){
        $this->requisitor = UsuarioControl::autenticar();
        $this->DAO = new CompraDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if (!is_null($this->url)) {
            $requisicaoEncontrada = false;
            switch ($this->metodo) {
                case 'POST':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/compras/
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($info);
                        $this->respostaSucesso("Compra cadastrada com sucesso",null, $this->requisitor);
                    }
                    break;
                case 'GET':
                    // /api/compras/
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        if ($this->requisitor['admin'] == "1") {
                            $compras = $this->listar();
                            if ($compras != false ) {
                                $this->respostaSucesso("Listando todas as compras cadastradas no sistema",$compras, $this->requisitor);
                            } else {
                                $this->respostaSucesso("Nenhuma compra encontrada no sistema",null,$this->requisitor);
                            }
                        } else {
                            throw new PermissionException("Você precisa ser administrador para acessar todas as compras","Listar todas as compras");
                        }
                    }
                    // /api/compras/{idCompra}
                    elseif (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $compra = $this->listarPorId($this->url[2]);
                        if ($compra->getComprador()->getId() == $this->requisitor['id'] || $this->requisitor['admin'] == "1") {
                            $compras = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando compra de id: {$this->url[2]}",$compras, $this->requisitor);
                        } else {
                            throw new PermissionException("Você não possui acesso aos detalhes desta compra","Acessar dados de compra feita por outro usuário");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/compras/{idCompra}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        if (isset($info->proposito) && isset($info->fonte) && isset($info->natOrcamentaria)) {
                            $this->atualizar($info,$this->url[2]);
                            $this->respostaSucesso("Compra atualizada com sucesso",null,$this->requisitor);
                        } else {
                            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/compras/{idCompra}
                    if (count($this->url) == 3 && $this->url[2] == (int)$this->url[2]) {
                        $requisicaoEncontrada = true;
                        $this->excluir($this->url[2]);
                        $this->respostaSucesso("Compra excluída com sucesso",null,$this->requisitor);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function cadastrar($info)
    {
        if (isset($info->proposito) && isset($info->idTarefa) && isset($info->itens) && is_array($info->itens) && isset($info->fonte) && isset($info->natOrcamentaria) ) {
            if ($this->verificaPermissao($info->idTarefa)) {
                $usuarioControl = new UsuarioControl(null);
                $usuario = $usuarioControl->listarPorId($this->requisitor['id']);
                //Cadastra no banco de dados com Total = 0
                $compra = new CompraModel($info->proposito,null,null,null,$usuario,$info->fonte,$info->natOrcamentaria);
                $this->DAO->cadastrar($compra,$info->idTarefa);
                //Pega id da Compra Inserida
                $idCompra = $this->DAO->pdo->lastInsertId();
                //Cadastra no banco todos os itens da Compra
                $itemControl = new ItemControl();
                foreach ($info->itens as $item) {
                    $itemControl->cadastrar($item->valor,$item->nome,$item->quantidade,$idCompra);
                }
            } else {
                throw new PermissionException("Você não possui permissão para cadastrar compras neste projeto","Cadastrar compra em projeto que não está inserido");
            }
        } else {
            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
        }
    }

    protected function excluir($id)
    {
        $compra = $this->listarPorId($id);

        if ($compra->getComprador()->getId() == $this->requisitor['id'] ) {
            $idTarefa = $this->DAO->descobreIdTarefa($id);
            $this->DAO->excluir($id);
            $tarefaControl = new TarefaControl(null);
            $tarefaControl->atualizaTotal($idTarefa);
        } else {
            throw new PermissionException("Usuário não possui permissão para excluir esta compra","Excluir compra feita por outra pessoa");
        }
    }

    public function atualizar($body,$id)
    {
        $compra = $this->listarPorId($id);
        if ($compra->getComprador()->getId() == $this->requisitor['id']) {
            $compraNova = new CompraModel($body->proposito,null,null,$id,null,$body->fonte,$body->natOrcamentaria);
            $this->DAO->atualizar($compraNova);
        } else {
                throw new PermissionException("Usuário não possui permissão para Atualizar esta compra","Atualizar compra feita por outra pessoa");
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
                throw new PermissionException("Permissão de acesso a Compra Negada","Acessar compra realizada por outro usuário");
            }
        } else {
            throw new NotFoundException("Compra não encontrada no sistema");
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
            throw new NotFoundException("Compra não encontrada no sistema");
        }
    }
}
