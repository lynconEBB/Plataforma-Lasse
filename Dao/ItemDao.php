<?php

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
        $comando = "DELETE FROM tbItem WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    function excluirPorIdCompra($idCompra){
        $comando = "DELETE FROM tbItem WHERE idCompra = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$idCompra);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * from tbItem";
        $stm = $this->pdo->prepare($comando);

        $stm->execute();
        $result = [];
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new ItemModel($row['valor'],$row['nome'],$row['quantidade'],$row['id']);
            $result[] = $obj;
        }

        return $result;
    }

    function atualizar(ItemModel $item){

        $comando = "UPDATE tbItem SET nome=:nome,valor=:valor,quantidade=:quantidade WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$item->getNome());
        $stm->bindValue(':valor',$item->getValor());
        $stm->bindValue(':quantidade',$item->getQuantidade());
        $stm->bindValue(':id',$item->getId());

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
        $result = [];
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new ItemModel($row['valor'],$row['nome'],$row['quantidade'],$row['id']);
            $result[] = $obj;
        }

        return $result;
    }

}