<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\GastoModel;
use PDO;

class GastoDao extends CrudDao
{

    function cadastrar(GastoModel $gasto,$idViagem){
        $comando = "INSERT INTO tbGasto (tipo,valor,idViagem) values (:tipo, :valor, :idViagem)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$gasto->getTipo());
        $stm->bindValue(':valor',$gasto->getValor());
        $stm->bindValue(':idViagem',$idViagem);

        $stm->execute();
    }

    function excluir($id){
        $comando = "DELETE FROM tbGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
    }

    public function listarPorIdViagem($idViagem){
        $comando = "SELECT * FROM tbGasto WHERE idViagem= :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idViagem);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new GastoModel($row['valor'],$row['tipo'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listar(){
        $comando = "SELECT * FROM tbGasto";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            $result = array();
            foreach ($rows as $row){
                $obj = new GastoModel($row['valor'],$row['tipo'],$row['id']);
                $result[] = $obj;
            }
            return $result;
        } else {
            return false;
        }
    }

    public function listarPorId($id){
        $comando = "SELECT * FROM tbGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        if ($row != false) {
            $obj = new GastoModel($row['valor'],$row['tipo'],$row['id']);
            return $obj;
        } else {
            return false;
        }
    }

    function atualizar(GastoModel $gasto){
        $comando = "UPDATE tbGasto SET tipo=:tipo, valor=:valor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$gasto->getTipo());
        $stm->bindValue(':valor',$gasto->getValor());
        $stm->bindValue(':id',$gasto->getId());

        $stm->execute();
    }

    public function descobrirIdViagem($id)
    {
        $comando = "SELECT idViagem FROM tbGasto where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        return $row['idViagem'];
    }

}