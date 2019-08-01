<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\ProjetoModel;
use PDO;

class ProjetoDao extends CrudDao {

    public function cadastrar(ProjetoModel $projeto){
        $comando1 = "INSERT INTO tbProjeto (nome,descricao,dataFinalizacao,dataInicio) values (:nome, :descr, :dtFim,:dtInicio)";
        $stm = $this->pdo->prepare($comando1);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtFim',$projeto->getDataFinalizacao()->format('Y-m-d'));
        $stm->bindValue(':dtInicio',$projeto->getDataInicio()->format('Y-m-d'));

        $stm->execute();

        $comando2 = 'INSERT INTO tbUsuarioProjeto (idProjeto,idUsuario,dono) values (:idProjeto, :idUsuario,TRUE)';
        $stm = $this->pdo->prepare($comando2);

        $stm->bindValue(':idProjeto',$this->pdo->lastInsertId());
        $stm->bindValue(':idUsuario',$_SESSION['usuario-id']);

        $stm->execute();
    }

    public function excluir($id){
        $comando2 = "DELETE FROM tbProjeto WHERE id = :id";
        $stm = $this->pdo->prepare($comando2);
        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * FROM tbProjeto";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        $linhas = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($linhas) > 0) {
            $tarefaDAO = new TarefaDao();
            $usuarioDAO = new UsuarioDao();

            foreach ($linhas as $row) {
                $tarefas = $tarefaDAO->listarPorIdProjeto($row['id']);
                $participantes = $usuarioDAO->listarPorIdProjeto($row['id']);
                $obj = new ProjetoModel($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id'],$tarefas,$row['totalGasto'],$participantes);
                $result[] = $obj;
            }
            return $result;
        }else{
            return false;
        }
    }

    public function listarPorIdUsuario($id){
        $comando1 = "select tbProjeto.dataFinalizacao, tbProjeto.dataInicio, tbProjeto.descricao, tbProjeto.nome, tbProjeto.id, tbProjeto.totalGasto from tbProjeto inner join tbUsuarioProjeto on tbProjeto.id = tbUsuarioProjeto.idProjeto where tbUsuarioProjeto.idUsuario = :id";
        $stm = $this->pdo->prepare($comando1);
        $stm->bindValue(':id',$id);
        $stm->execute();
        $projetos = array();
        $linhas = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (count($linhas) > 0) {
            $tarefaDAO = new TarefaDao();
            $usuarioDAO = new UsuarioDao();

            foreach ($linhas as $row) {
                $tarefas = $tarefaDAO->listarPorIdProjeto($row['id']);
                $participantes = $usuarioDAO->listarPorIdProjeto($row['id']);
                $obj = new ProjetoModel($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id'],$tarefas,$row['totalGasto'],$participantes);
                $projetos[] = $obj;
            }
            return $projetos;
        }else {
            return false;
        }

    }

    public function listarPorId($id){
        $comando = "SELECT * FROM tbProjeto where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        if ($row != false){
            $tarefaDAO = new TarefaDao();
            $tarefas = $tarefaDAO->listarPorIdProjeto($id);
            $usuarioDAO = new UsuarioDao();
            $usuarios = $usuarioDAO->listarPorIdProjeto($id);

            $projeto = new ProjetoModel($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id'],$tarefas,null,$usuarios);
            return $projeto;
        }else{
            return false;
        }
    }

    public function alterar(ProjetoModel $projeto){

        $comando = "UPDATE tbProjeto SET nome=:nome,descricao=:descr,dataFinalizacao=:dtfim, dataInicio=:dtini WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtfim',$projeto->getDataFinalizacao()->format('Y-m-d'));
        $stm->bindValue(':dtini',$projeto->getDataInicio()->format('Y-m-d'));
        $stm->bindValue(':id',$projeto->getId());

        $stm->execute();
    }

    public function atualizarTotal(ProjetoModel $projeto){

        $comando = "UPDATE tbProjeto SET totalGasto = :totalGasto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':totalGasto',$projeto->getTotalGasto());
        $stm->bindValue(':id',$projeto->getId());

        $stm->execute();
    }

    public function procuraFuncionario($idProjeto,$idUsuario){
        $comando = "select * from tbUsuarioProjeto where idProjeto = :idProjeto and idUsuario = :idUsuario";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$idUsuario);

        $stm->execute();
        return $stm->rowCount();
    }

    public function verificaDono($idProjeto)
    {
        $comando = "select * from tbUsuarioProjeto where idProjeto = :idProjeto and idUsuario = :idUsuario and dono = TRUE";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$_SESSION['usuario-id']);

        $stm->execute();
        return $stm->rowCount();
    }

    public function adicionarFuncionario($idUsuario,$idProjeto)
    {
        $comando = "insert into tbUsuarioProjeto (idProjeto,idUsuario,dono) values (:idProjeto,:idUsuario,FALSE)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$idUsuario);

        $stm->execute();
    }
}
