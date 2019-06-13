<?php

require_once '../Services/Autoload.php';

class CompraDao extends CrudDao {

    function cadastrar(CompraModel $compra,$idTarefa){
        $comando = "INSERT INTO tbCompra (proposito,totalGasto,idTarefa) values (:proposito, :totalGasto, :idTarefa)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':proposito',$compra->getProposito());
        $stm->bindValue(':totalGasto',$compra->getTotalGasto());
        $stm->bindValue(':idTarefa',$idTarefa);
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
        $result =array();

        $itemControl = new ItemControl();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $itens = $itemControl->listarPorIdCompra($row['id']);
            $obj = new CompraModel($row['proprosito'],$row['totalGasto'],$itens,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(CompraModel $compra,$idTarefa){

        $comando = "UPDATE tbCompra SET proposito = :proposito, idTarefa = :idTarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':proposito',$compra->getProposito());
        $stm->bindValue(':idTarefa',$idTarefa);
        $stm->bindValue(':id',$compra->getId());

        $stm->execute();

    }

    function atualizaTotal($idCompra,$novoTotal){

        $comando = "UPDATE tbCompra SET totalGasto = :totalGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':totalGasto',$novoTotal);
        $stm->bindValue(':id',$idCompra);

        $stm->execute();
    }

    public function listarPorIdTarefa($idTarefa){
        $comando = "SELECT * FROM tbCompra where idTarefa = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idTarefa);
        $stm->execute();
        $result =array();

        $itemControl = new ItemControl();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $itens = $itemControl->listarPorIdCompra($row['id']);
            $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listarPorId($id):CompraModel
    {
        $comando = "SELECT * FROM tbCompra where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();

        $itemControl = new ItemControl();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
            $itens = $itemControl->listarPorIdCompra($row['id']);
            $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id']);
        return $obj;
    }

}