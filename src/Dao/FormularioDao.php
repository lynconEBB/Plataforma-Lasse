<?php


namespace Lasse\LPM\Dao;


use Lasse\LPM\Control\CompraControl;
use Lasse\LPM\Control\UsuarioControl;
use Lasse\LPM\Control\ViagemControl;
use Lasse\LPM\Model\FormularioModel;
use PDO;

class FormularioDao extends CrudDao
{
    public function listarPorUsuarioNome($nome,$idUsuario)
    {
        $comando = "SELECT * FROM tbFormulario WHERE nome = :nome and idUsuario = :idUsuario";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':nome',$nome);
        $stm->bindParam(':idUsuario',$idUsuario);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $usuarioControl = new UsuarioControl(null);
        $viagemControl = new ViagemControl(null);
        $compraControl = new CompraControl(null);
        $usuario = $usuarioControl->listarPorId($idUsuario);
        if (count($rows) > 0) {
            $objs = array();
            foreach ($rows as $row) {
                if ($row['idViagem'] != null) {
                    $viagem = $viagemControl->listarPorId($row['idViagem']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$viagem,$row['idCompra']);
                } else {
                    $compra = $compraControl->listarPorId($row['idCompra']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$row['idViagem'],$compra);
                }
                $objs[] = $formulario;
            }
            return $objs;
        } else {
            return false;
        }
    }

    public function excluir($id)
    {
        $comando = "DELETE FROM tbFormulario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue('id',$id);

        $stm->execute();
    }

    public function listarPorId($id)
    {
        $comando = "SELECT * FROM tbFormulario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);

        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        $usuarioControl = new UsuarioControl(null);
        $viagemControl = new ViagemControl(null);
        $compraControl = new CompraControl(null);

        if ($row != false) {
            $usuario = $usuarioControl->listarPorId($row['idUsuario']);
            if ($row['idViagem'] != null) {
                $viagem = $viagemControl->listarPorId($row['idViagem']);
                $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$viagem,$row['idCompra']);
            } else {
                $compra = $compraControl->listarPorId($row['idCompra']);
                $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$row['idViagem'],$compra);
            }

            return $formulario;
        } else {
            return false;
        }
    }

    public function cadastrar(FormularioModel $formulario)
    {
        $comando = "INSERT INTO tbFormulario (nome, caminhoDocumento,idUsuario,idCompra,idViagem,dataModificacao) VALUES (:nome,:caminhoDoc,:idUsuario,:idCompra,:idViagem,:dataModificacao)";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':nome',$formulario->getNome());
        $stm->bindValue(':caminhoDoc',$formulario->getCaminhoDocumento());
        $stm->bindValue(':idUsuario',$formulario->getUsuario()->getId());
        $stm->bindValue(":dataModificacao",$formulario->getDataModificacao()->format("Y-m-d"));

        if ($formulario->getViagem() === null) {
            $stm->bindValue(':idCompra',$formulario->getCompra()->getId());
            $stm->bindValue(':idViagem',$formulario->getViagem());
        } else {
            $stm->bindValue(':idCompra',$formulario->getCompra());
            $stm->bindValue(':idViagem',$formulario->getViagem()->getId());
        }
        $stm->execute();
    }

    public function listar()
    {
        $comando = "SELECT * FROM tbFormulario";
        $stm = $this->pdo->prepare($comando);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $usuarioControl = new UsuarioControl(null);
        $viagemControl = new ViagemControl(null);
        $compraControl = new CompraControl(null);
        if (count($rows) > 0) {
            $objs = array();
            foreach ($rows as $row) {
                $usuario= $usuarioControl->listarPorId($row['idUsuario']);
                if ($row['idViagem'] != null) {
                    $viagem = $viagemControl->listarPorId($row['idViagem']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$viagem,$row['idCompra']);
                } else {
                    $compra = $compraControl->listarPorId($row['idCompra']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$row['idViagem'],$compra);
                }
                $objs[] = $formulario;
            }
            return $objs;
        } else {
            return false;
        }
    }

    public function listarPorIdUsuario($idUsuario)
    {
        $comando = "SELECT * FROM tbFormulario WHERE idUsuario = :idUsuario";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':idUsuario',$idUsuario);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $usuarioControl = new UsuarioControl(null);
        $viagemControl = new ViagemControl(null);
        $compraControl = new CompraControl(null);
        if (count($rows) > 0) {
            $objs = array();
            foreach ($rows as $row) {
                $usuario= $usuarioControl->listarPorId($row['idUsuario']);
                if ($row['idViagem'] != null) {
                    $viagem = $viagemControl->listarPorId($row['idViagem']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$viagem,$row['idCompra']);
                } else {
                    $compra = $compraControl->listarPorId($row['idCompra']);
                    $formulario = new FormularioModel($row['nome'],$usuario,$row['dataModificacao'],$row['caminhoDocumento'],$row['id'],$row['idViagem'],$compra);
                }
                $objs[] = $formulario;
            }
            return $objs;
        } else {
            return false;
        }
    }
}
