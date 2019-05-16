<?php
require_once '../Control/LoginControl.php';
require_once 'CrudDAO.php';
require_once 'FuncionarioDAO.php';
require_once '../Model/Projeto.php';

class ProjetoDAO extends CrudDAO {

    function __construct(){
        LoginControl::verificar();
        parent::__construct();
    }

    function cadastrar(Projeto $projeto){
        $comando1 = "INSERT INTO tbProjeto (nome,descricao,dataFinalizacao,dataInicio) values (:nome, :descr, :dtFim,:dtInicio)";
        $stm = $this->pdo->prepare($comando1);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtFim',$projeto->getDataFinalizacao());
        $stm->bindValue(':dtInicio',$projeto->getDataInicio());

        $stm->execute();

        $comando2 = 'INSERT INTO tbUsuarioProjeto (idProjeto,idUsuario) values (:idProjeto, :idUsuario)';
        $stm = $this->pdo->prepare($comando2);

        $stm->bindValue(':idProjeto',$this->pdo->lastInsertId());
        $stm->bindValue(':idUsuario',$_SESSION['usuario-id']);

        $stm->execute();

        header('Location:../View/projetoView.php?success=true');
    }

    function excluir($id){
        $comando1 = "DELETE FROM tbUsuarioProjeto WHERE idProjeto = :id";
        $stm = $this->pdo->prepare($comando1);
        $stm->bindParam(':id',$id);
        $stm->execute();

        $comando2 = "DELETE FROM tbProjeto WHERE id = :id";
        $stm = $this->pdo->prepare($comando2);
        $stm->bindParam(':id',$id);
        $stm->execute();

        header('Location:../View/projetoView.php?success=true');
    }

    function listar(){
        $comando = "SELECT * FROM tbProjeto";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new Projeto($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listarPorIdUsuario($id){
        $comando1 = "select idProjeto from tbUsuarioProjeto where idUsuario = :id";
        $stm = $this->pdo->prepare($comando1);
        $stm->bindValue(':id',$id);
        $stm->execute();
        $idProjetos = $stm->fetchAll(PDO::FETCH_COLUMN);

        $projetos = array();
        foreach ($idProjetos as $idProjeto){
            $projetos[] = $this->listarPorId($idProjeto);
        }
        return $projetos;
    }

    public function listarPorId($id){
        $comando = "SELECT * FROM tbProjeto where id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindValue(':id',$id);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $projeto = new Projeto($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id']);
        return $projeto;
    }
    function alterar(Projeto $projeto){

        $comando = "UPDATE tbprojeto SET nome=:nome,descricao=:descr,dataFinalizacao=:dtfim, dataInicio=:dtini WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtfim',$projeto->getDataFinalizacao());
        $stm->bindValue(':dtini',$projeto->getDataInicio());
        $stm->bindValue(':id',$projeto->getId());

        $stm->execute();
        header('Location:../View/projetoView.php?success=true');
    }
}
