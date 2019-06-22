<?php

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
        $result =array();
        $usuarioDAO = new UsuarioDao();
        $itemControl = new ItemControl();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $itens = $itemControl->listarPorIdCompra($row['id']);
            $comprador = $usuarioDAO->listarPorId($row['idComprador']);
            $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id'],$comprador);
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

        $usuarioDAO = new UsuarioDao();
        $itemDAO = new ItemDao();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $itens = $itemDAO->listarPorIdCompra($row['id']);
            $comprador = $usuarioDAO->listarPorId($row['idComprador']);
            $obj = new CompraModel($row['proposito'],$row['totalGasto'],$itens,$row['id'],$comprador);
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

        $itemDAO = new ItemDao();
        $itens = $itemDAO->listarPorIdCompra($id);
        $usuarioDAO = new UsuarioDao();


        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $comprador = $usuarioDAO->listarPorId($row['idComprador']);
        $obj = new CompraModel($row['proposito'],0,$itens,$row['id'],$comprador);
        return $obj;
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