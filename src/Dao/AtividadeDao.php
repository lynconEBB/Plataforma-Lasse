<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Control\UsuarioControl;
use Lasse\LPM\Model\AtividadeModel;
use PDO;

class AtividadeDao extends CrudDao {

    function cadastrar(AtividadeModel $atividade,$idTarefa){
        $comando = "INSERT INTO tbAtividade (tipo,comentario,tempoGasto,dataRealizacao,totalGasto,idTarefa,idUsuario) values (:tipo,:comentario,:tempoGasto,:dataRealizacao,:totalGasto,:idTarefa,:idUsuario)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$atividade->getTipo());
        $stm->bindValue(':comentario',$atividade->getComentario());
        $stm->bindValue(':tempoGasto',$atividade->getTempoGasto());
        $stm->bindValue(':dataRealizacao',$atividade->getDataRealizacao()->format('Y-m-d'));
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

        $usuarioControl = new UsuarioControl(null);
        $linhas = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (count($linhas) > 0){
            foreach ($linhas as $row) {
                $usuario = $usuarioControl->listarPorId($row['idUsuario']);
                $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id'],$row['totalGasto']);
                $result[] = $obj;
            }
            return $result;
        }else{
            return false;
        }

    }

    function atualizar(AtividadeModel $atividade){

        $comando = "UPDATE tbAtividade SET tipo = :tipo, tempoGasto = :tempoGasto, dataRealizacao = :dataRealizacao, totalGasto = :totalGasto, comentario = :comentario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':tipo',$atividade->getTipo());
        $stm->bindValue(':comentario',$atividade->getComentario());
        $stm->bindValue(':tempoGasto',$atividade->getTempoGasto());
        $stm->bindValue(':dataRealizacao',$atividade->getDataRealizacao()->format('Y-m-d'));
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
        $linhas = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($linhas) > 0){
            $usuarioDAO = new UsuarioDao();
            foreach ($linhas as $row){
                $usuario = $usuarioDAO->listarPorId($row['idUsuario']);
                $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id'],$row['totalGasto']);
                $result[] = $obj;
            }
            return $result;
        }else{
            return false;
        }
    }

    public function listarPorIdUsuario($idUsuario){
        $comando = "SELECT * FROM tbAtividade where idUsuario = :id AND idTarefa IS NULL";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idUsuario);
        $stm->execute();
        $result =array();
        $linhas = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(count($linhas) > 0){
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($idUsuario);
            foreach ($linhas as $row) {
                $obj = new AtividadeModel($row['tipo'],$row['tempoGasto'],$row['comentario'],$row['dataRealizacao'],$usuario,$row['id'],$row['totalGasto']);
                $result[] = $obj;
            }
            return $result;
        }else{
            return false;
        }
    }

    public function listarPorId($id){
        $comando = "SELECT * FROM tbAtividade where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$id);
        $stm->execute();
        $linha = $stm->fetch(PDO::FETCH_ASSOC);

        if($linha != false){
            $usuarioControl = new UsuarioControl(null);
            $usuario = $usuarioControl->listarPorId($linha['idUsuario']);
            $obj = new AtividadeModel($linha['tipo'],$linha['tempoGasto'],$linha['comentario'],$linha['dataRealizacao'],$usuario,$linha['id'],$linha['totalGasto']);
            return $obj;
        }else{
            return false;
        }

    }


    public function descobrirIdTarefa($id)
    {
        $comando = "SELECT idTarefa from tbAtividade WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        return $row['idTarefa'];
    }

    public function listarAtividadesPeriodo($idUsuario,$primeiroDia) {
        $comando = "select date_format(dataRealizacao,'%d/%m/%Y'),tempoGasto,tP.nome from tbAtividade inner join tbTarefa tT on tbAtividade.idTarefa = tT.id inner join tbProjeto tP on tT.idProjeto = tP.id 
            where idUsuario = :idUsuario and idTarefa is not NULL and dataRealizacao >= :primeiroDia and dataRealizacao < :primeiroDia + INTERVAL 1 MONTH ";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':idUsuario',$idUsuario);
        $stm->bindParam(":primeiroDia",$primeiroDia);
        $stm->execute();

        $result =  $stm->fetchAll();
        if (count($result) > 0 ) {
            return $result;
        } else {
            return false;
        }
    }
}
