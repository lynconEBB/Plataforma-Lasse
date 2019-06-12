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

    function atualizar(CompraModel $compra){

        $comando = "UPDATE tbCondutor SET nome = :nome, cnh = :cnh, validadeCNH = :validadeCNH WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());
        $stm->bindValue(':id',$condutor->getId());

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

}