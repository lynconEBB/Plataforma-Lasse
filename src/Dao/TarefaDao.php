<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\TarefaModel;
use PDO;

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
        $atividadeDAO = new AtividadeDao();
        $viagemDAO = new ViagemDao();
        $compraDAO = new CompraDao();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $atividades = $atividadeDAO->listarPorIdTarefa($row['id']);
            $compras = $compraDAO->listarPorIdTarefa($row['id']);
            $viagens = $viagemDAO->listarPorIdTarefa($row['id']);
            $obj = new TarefaModel($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id'],$atividades,$viagens,$compras,$row['totalGasto']);
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

    function atualizaTotal(TarefaModel $tarefa){
        $comando = "UPDATE tbTarefa SET totalGasto = :totalGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':totalGasto',$tarefa->getTotalGasto());
        $stm->bindValue(':id',$tarefa->getId());

        $stm->execute();
    }

    public function listarPorIdProjeto($id){
        $comando = "SELECT * from tbTarefa WHERE idProjeto = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();
        $atividadeDAO = new AtividadeDao();
        $viagemDAO = new ViagemDao();
        $compraDAO = new CompraDao();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $atividades = $atividadeDAO->listarPorIdTarefa($row['id']);
            $compras = $compraDAO->listarPorIdTarefa($row['id']);
            $viagens = $viagemDAO->listarPorIdTarefa($row['id']);
            $obj = new TarefaModel($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id'],$atividades,$viagens,$compras,$row['totalGasto']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listarPorId($id){
        $comando = "SELECT * from tbTarefa WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $viagemDAO = new ViagemDao();
        $viagens = $viagemDAO->listarPorIdTarefa($id);
        $atividadeDAO = new AtividadeDao();
        $atividades = $atividadeDAO->listarPorIdTarefa($id);
        $compraDAO = new CompraDao();
        $compras = $compraDAO->listarPorIdTarefa($id);

        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $obj = new TarefaModel($row['nome'],$row['descricao'],$row['estado'],$row['dataInicio'],$row['dataConclusao'],$row['id'],$atividades,$viagens,$compras,null);

        return $obj;
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