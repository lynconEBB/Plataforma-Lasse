<?php

require_once '../Services/Autoload.php';

class AtividadeDao extends CrudDao {

    function cadastrarPlanejado(AtividadeModel $atividade,$idTarefa){
        $comando = "INSERT INTO tbAtividade (tipo,comentario,tempoGasto,dataRealizacao,totalGasto,idTarefa,idUsuario) values (:tipo,:comentario,:tempoGasto,:dataRealizacao,:totalGasto,:idTarefa,:idUsuario)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$atividade->getTipo());
        $stm->bindValue(':comentario',$atividade->getComentario());
        $stm->bindValue(':tempoGasto',$atividade->getTempoGasto());
        $stm->bindValue(':dataRealizacao',$atividade->getDataRealizacao());
        $stm->bindValue(':totalGasto',$atividade->getTotalGasto());
        $stm->bindValue(':idTarefa',$idTarefa);
        $stm->bindValue(':idUsuario',$atividade->getUsuario()->getId());
        $stm->execute();
    }

    function excluir($id){
        $comando = "DELETE FROM tbAtividade WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * FROM tbAtividade";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();

        $usuarioControl = new UsuarioControl();

        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $usuario = $usuarioControl->listarPorId($row['idUsuario']);
            $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(AtividadeModel $atividade){

        $comando = "UPDATE tbAtividade SET tipo = :tipo, tempoGasto = :tempoGasto, dataRealizacao = :dataRealizacao, totalGasto = :totalGasto, comentario = :comentario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$atividade->getTipo());
        $stm->bindValue(':comentario',$atividade->getComentario());
        $stm->bindValue(':tempoGasto',$atividade->getTempoGasto());
        $stm->bindValue(':dataRealizacao',$atividade->getDataRealizacao());
        $stm->bindValue(':totalGasto',$atividade->getTotalGasto());
        $stm->bindValue(':id',$atividade->getId());

        $stm->execute();
    }


    public function listarPorIdTarefa($idTarefa){
        $comando = "SELECT * FROM tbAtividade where idTarefa = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idTarefa);
        $stm->execute();
        $result =array();

        $usuarioControl = new UsuarioControl();

        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $usuario = $usuarioControl->listarPorId($row['idUsuario']);
            $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listarPorIdUsuario($idUsuario){
        $comando = "SELECT * FROM tbAtividade where idUsuario = :id AND idTarefa IS NULL";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idUsuario);
        $stm->execute();
        $result =array();

        $usuarioControl = new UsuarioControl();
        $usuario = $usuarioControl->listarPorId($idUsuario);
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

}