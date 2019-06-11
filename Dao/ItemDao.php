<?php

require_once '../Services/Autoload.php';

class ItemDao extends CrudDao {
    function cadastrar(ItemModel $item,$idCompra){
        $comando = "INSERT INTO tbItem (valor,quantidade,nome,idCompra) values (:valor, :quantidade,:nome,:idCompra)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':valor',$item->getValor());
        $stm->bindValue(':quantidade',$item->getQuantidade());
        $stm->bindValue(':nome',$item->getNome());
        $stm->bindValue(':idCompra',$idCompra);

        $stm->execute();
    }

    function excluir($id){
        $comando = "DELETE FROM tbCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * FROM tbCondutor";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new CondutorModel($row['nome'],$row['cnh'],$row['validadeCNH'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(CondutorModel $condutor){

        $comando = "UPDATE tbCondutor SET nome=:nome,cnh=:cnh,validadeCNH=:validadeCNH WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());
        $stm->bindValue(':id',$condutor->getId());

        $stm->execute();

    }

    public function listarPorId($id){
        $comando = "SELECT * from tbCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':id',$id);

        $stm->execute();
        $resul = $stm->fetch(PDO::FETCH_ASSOC);
        $obj = new CondutorModel($resul['nome'],$resul['cnh'],$resul['validadeCNH'],$resul['id']);

        return $obj;
    }

    public function listarPorIdCompra($id){
        $comando = "SELECT * from tbItem WHERE idCompra = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':id',$id);

        $stm->execute();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new ItemModel($row['valor'],$row['nome'],$row['quantidade'],$row['id']);
            $result[] = $obj;
        }

        return $result;
    }
}