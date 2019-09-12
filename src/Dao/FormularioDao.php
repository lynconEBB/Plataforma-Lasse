<?php


namespace Lasse\LPM\Dao;


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

        if (count($rows) > 0) {
            $objs = array();
            foreach ($rows as $row) {
                $formulario = new FormularioModel($row['nome'],$row['caminhoDocumento'],$row['caminhoHTML'],$row['id']);
                $objs[] = $formulario;
            }
            return $objs;
        } else {
            return false;
        }
    }

    public function excluir($id)
    {

    }

    public function cadastrar(FormularioModel $formulario,$idViagem,$idCompra)
    {
        $comando = "INSERT INTO tbFormulario (nome, caminhoDocumento, caminhoHTML,idUsuario,idCompra,idViagem) VALUES (:nome,:caminhoDoc,:caminhoHtml,:idUsuario,:idCompra,:idViagem)";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':nome',$formulario->getNome());
        $stm->bindValue(':caminhoDoc',$formulario->getCaminhoDocumento());
        $stm->bindValue(':caminhoHtml',$formulario->getCaminhoHTML());
        $stm->bindValue(':idUsuario',$formulario->getUsuario()->getId());
        $stm->bindValue(':idCompra',$idCompra);
        $stm->bindValue(':idViagem',$idViagem);

        $stm->execute();
    }

    public function listar()
    {
        // TODO: Implement listar() method.
    }
}