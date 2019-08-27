<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\VeiculoModel;
use PDO;

class VeiculoDao extends CrudDao {

    function cadastrar(VeiculoModel $veiculo){
        $comando = "INSERT INTO tbVeiculo (nome,tipo,idCondutor,retirada,devolucao) values (:nome, :tipo, :idCondutor, :retirada,:devolucao)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$veiculo->getNome());
        $stm->bindValue(':tipo',$veiculo->getTipo());
        $stm->bindValue(':idCondutor',$veiculo->getCondutor()->getId());
        $stm->bindValue(':retirada',$veiculo->getRetirada()->format("Y-m-d H:i:s"));
        $stm->bindValue(':devolucao',$veiculo->getDevolucao()->format("Y-m-d H:i:s"));

        $stm->execute();
    }


    function excluir($id){
        $comando = "DELETE FROM tbVeiculo WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
    }


    public function listar(){
        $comando = "SELECT * FROM tbVeiculo";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result = array();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            $condutorDAO = new CondutorDao();
            foreach ($rows as $row) {
                $condutor = $condutorDAO->listarPorId($row['idCondutor']);
                $obj = new VeiculoModel($row['nome'],$row['tipo'],$row['retirada'],$row['devolucao'],$condutor,$row['id']);
                $result[] = $obj;
            }
            return $result;
        } else {
            return false;
        }
    }

    function atualizar(VeiculoModel $veiculo){

        $comando = "UPDATE tbVeiculo SET nome=:nome,tipo=:tipo,retirada=:retirada, devolucao = :devolucao, idCondutor = :idCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$veiculo->getNome());
        $stm->bindValue(':tipo',$veiculo->getTipo());
        $stm->bindValue(':idCondutor',$veiculo->getCondutor()->getId());
        $stm->bindValue(':retirada',$veiculo->getRetirada()->format("Y-m-d H:i:s"));
        $stm->bindValue(':devolucao',$veiculo->getDevolucao()->format("Y-m-d H:i:s"));

        $stm->bindValue(':id',$veiculo->getId());

        $stm->execute();
    }

    public function listarPorId($id){
        $comando = "SELECT * from tbVeiculo WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        if($row != false) {
            $condutorDAO = new CondutorDao();
            $condutor = $condutorDAO->listarPorId($row['idCondutor']);
            $obj = new VeiculoModel($row['nome'],$row['tipo'],$row['retirada'],$row['devolucao'],$condutor,$row['id']);
            return $obj;
        } else {
            return false;
        }
    }
}