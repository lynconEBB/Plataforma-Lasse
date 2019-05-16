<?php
require_once 'CrudDAO.php';
require_once '../Model/Tarefa.php';

class TarefaDAO extends CrudDAO {

    function cadastrar(Tarefa $tarefa, $idProjeto){
        $comando = "INSERT INTO tbTarefa (nome,descricao,estado,dataInicio,dataConclusao,idProjeto) values (:nome, :descr, :estado, :dtInicio, :dtConclusao, :idProjeto)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$tarefa->getNome());
        $stm->bindValue(':descr',$tarefa->getDescricao());
        $stm->bindValue(':estado',$tarefa->getEstado());
        $stm->bindValue(':dtInicio',$tarefa->getDataInicio());
        $stm->bindValue(':dtConclusao',$tarefa->getDataConclusao());
        $stm->bindValue(':idProjeto',$idProjeto);

        $stm->execute();
        header('Location:../View/tarefaView.php');
    }

    function excluir($id){
        $comando = "DELETE FROM tbtarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
        header('Location:../View/tarefaView.php');
    }


    public function listar(){
        /*$comando = "SELECT * FROM tbcondutor";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new Condutor($row['nome'],$row['cnh'],$row['validadeCNH'],$row['id']);
            $result[] = $obj;
        }
        return $result;*/
    }

    function atualizar(Tarefa $tarefa){
        $comando = "UPDATE tbTarefa SET nome = :nome, estado = :estado, descricao = :descricao, dataInicio = :dataInicio, dataConclusao = :dataConclusao WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$tarefa->getNome());
        $stm->bindValue(':descricao',$tarefa->getDescricao());
        $stm->bindValue(':estado',$tarefa->getEstado());
        $stm->bindValue(':dataInicio',$tarefa->getDataInicio());
        $stm->bindValue(':dataConclusao',$tarefa->getDataConclusao());
        $stm->bindValue(':id',$tarefa->getId());

        $stm->execute();
        header('Location:../View/tarefaView.php');
    }

    public function listarPorIdProjeto($id){
        $comando = "SELECT * from tbtarefa WHERE idProjeto = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new Tarefa($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }
}