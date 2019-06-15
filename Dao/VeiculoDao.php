<?php

class VeiculoDao extends CrudDao {

    function cadastrar(VeiculoModel $veiculo){
        $comando = "INSERT INTO tbVeiculo (nome,tipo,idCondutor,dataRetirada,dataDevolucao,horarioRetirada,horarioDevolucao) values (:nome, :tipo, :idCondutor, :dtRetirada, :dtDevolucao, :horaRetirada, :horaDevolucao )";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$veiculo->getNome());
        $stm->bindValue(':tipo',$veiculo->getTipo());
        $stm->bindValue(':idCondutor',$veiculo->getCondutor()->getId());
        $stm->bindValue(':dtRetirada',$veiculo->getDataRetirada());
        $stm->bindValue(':dtDevolucao',$veiculo->getDataDevolucao());
        $stm->bindValue(':horaRetirada',$veiculo->getHorarioRetirada());
        $stm->bindValue(':horaDevolucao',$veiculo->getHorarioDevolucao());

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
        $result =array();
        $condutorDAO = new CondutorDao();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $condutor = $condutorDAO->listarPorId($row['idCondutor']);
            $obj = new VeiculoModel($row['nome'],$row['tipo'],$row['dataRetirada'],$row['dataDevolucao'],$row['horarioRetirada'],$row['horarioDevolucao'],$condutor,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(VeiculoModel $veiculo){

        $comando = "UPDATE tbVeiculo SET nome=:nome,tipo=:tipo,dataRetirada=:dtRetirada, dataDevolucao = :dtDevolucao, horarioRetirada = :horaRetirada, horarioDevolucao =:horaDevolucao, idCondutor = :idCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$veiculo->getNome());
        $stm->bindValue(':tipo',$veiculo->getTipo());
        $stm->bindValue(':idCondutor',$veiculo->getCondutor()->getId());
        $stm->bindValue(':dtRetirada',$veiculo->getDataRetirada());
        $stm->bindValue(':dtDevolucao',$veiculo->getDataDevolucao());
        $stm->bindValue(':horaRetirada',$veiculo->getHorarioRetirada());
        $stm->bindValue(':horaDevolucao',$veiculo->getHorarioDevolucao());
        $stm->bindValue(':id',$veiculo->getId());

        $stm->execute();
    }

    public function listarPorId($id){
        $comando = "SELECT * from tbVeiculo WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);

        $condutorDAO = new CondutorDao();
        $condutor = $condutorDAO->listarPorId($row['idCondutor']);

        $obj = new VeiculoModel($row['nome'],$row['tipo'],$row['dataRetirada'],$row['dataDevolucao'],$row['horarioRetirada'],$row['horarioDevolucao'],$condutor,$row['id']);

        return $obj;
    }
}