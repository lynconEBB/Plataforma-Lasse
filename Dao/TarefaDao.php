<?php
require_once '../Services/Autoload.php';

class TarefaDao extends CrudDao {

    function cadastrar(TarefaModel $tarefa, $idProjeto){
        $comando = "INSERT INTO tbTarefa (nome,descricao,estado,dataInicio,dataConclusao,idProjeto) values (:nome, :descr, :estado, :dtInicio, :dtConclusao, :idProjeto)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$tarefa->getNome());
        $stm->bindValue(':descr',$tarefa->getDescricao());
        $stm->bindValue(':estado',$tarefa->getEstado());
        $stm->bindValue(':dtInicio',$tarefa->getDataInicio());
        $stm->bindValue(':dtConclusao',$tarefa->getDataConclusao());
        $stm->bindValue(':idProjeto',$idProjeto);

        $stm->execute();
    }

    function excluir($id){
        $comando = "DELETE FROM tbTarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
    }


    public function listar(){
        $comando = "SELECT * FROM tbTarefa";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new TarefaModel($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(TarefaModel $tarefa){
        $comando = "UPDATE tbTarefa SET nome = :nome, estado = :estado, descricao = :descricao, dataInicio = :dataInicio, dataConclusao = :dataConclusao WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$tarefa->getNome());
        $stm->bindValue(':descricao',$tarefa->getDescricao());
        $stm->bindValue(':estado',$tarefa->getEstado());
        $stm->bindValue(':dataInicio',$tarefa->getDataInicio());
        $stm->bindValue(':dataConclusao',$tarefa->getDataConclusao());
        $stm->bindValue(':id',$tarefa->getId());

        $stm->execute();
    }

    public function listarPorIdProjeto($id){
        $comando = "SELECT * from tbTarefa WHERE idProjeto = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new TarefaModel($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function descobrirIdProjeto($id){
        $comando = "SELECT idProjeto from tbTarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        return $row['idProjeto'];
    }


}