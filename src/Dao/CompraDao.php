<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Control\ItemControl;
use Lasse\LPM\Model\CompraModel;
use PDO;

class CompraDao extends CrudDao {

    function cadastrar(CompraModel $compra,$idTarefa){
        $comando = "INSERT INTO tbCompra (proposito,idTarefa,idComprador) values (:proposito, :idTarefa, :idComprador)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':proposito',$compra->getProposito());
        $stm->bindValue(':idTarefa',$idTarefa);
        $stm->bindValue(':idComprador',$compra->getComprador()->getId());
        $stm->execute();
    }

    function excluir($id){
        $comando = "DELETE FROM tbCompra WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * FROM tbCompra";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            $result =array();
            $usuarioDAO = new UsuarioDao();
            $itemControl = new ItemControl();
            foreach ($rows as $row){
                $itens = $itemControl->listarPorIdCompra($row['id']);
                $comprador = $usuarioDAO->listarPorId($row['idComprador']);
                $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id'],$comprador);
                $result[] = $obj;
            }
            return $result;
        } else {
            return false;
        }
    }

    function atualizar(CompraModel $compra,$idTarefa){

        $comando = "UPDATE tbCompra SET proposito = :proposito, idTarefa = :idTarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':proposito',$compra->getProposito());
        $stm->bindValue(':idTarefa',$idTarefa);
        $stm->bindValue(':id',$compra->getId());

        $stm->execute();

    }

    function atualizarTotal(CompraModel $compra){

        $comando = "UPDATE tbCompra SET totalGasto = :totalGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':totalGasto',$compra->getTotalGasto());
        $stm->bindValue(':id',$compra->getId());

        $stm->execute();
    }

    public function listarPorIdTarefa($idTarefa){
        $comando = "SELECT * FROM tbCompra where idTarefa = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idTarefa);
        $stm->execute();

        $result =array();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            $usuarioDAO = new UsuarioDao();
            $itemDAO = new ItemDao();
            foreach ($rows as $row){
                $itens = $itemDAO->listarPorIdCompra($row['id']);
                $comprador = $usuarioDAO->listarPorId($row['idComprador']);
                $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id'],$comprador);
                $result[] = $obj;
            }
            return $result;
        } else {
            return false;
        }
    }

    public function listarPorId($id)
    {
        $comando = "SELECT * FROM tbCompra where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);
        if ($row != false) {
            $itemDAO = new ItemDao();
            $itens = $itemDAO->listarPorIdCompra($id);
            $usuarioDAO = new UsuarioDao();
            $comprador = $usuarioDAO->listarPorId($row['idComprador']);
            $obj = new CompraModel($row['proposito'],null,$itens,$row['id'],$comprador);
            return $obj;
        } else {
            return false;
        }
    }

    public function descobreIdTarefa($idCompra)
    {
        $comando = "SELECT idTarefa FROM tbCompra where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idCompra);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        return $row['idTarefa'];
    }
}