<?php
require_once 'CrudDAO.php';
require_once '../Model/Projeto.php';
class ProjetoDAO extends CrudDAO {

    function cadastrar(Projeto $projeto){

        $comando = "INSERT INTO tbProjeto (nome,descricao,dataFinalizacao,dataInicio) values (:nome, :desc, :dtFim,:dtInicio)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':desc',$projeto->getDescricao());
        $stm->bindValue(':dtFim',$projeto->getDataFinalizacao());
        $stm->bindValue(':dtInicio',$projeto->getDataInicio());

        $stm->execute();
        header('Location:../View/projetoView.php?success=true');
    }

    function excluir($id){
        $comando = "DELETE FROM tbProjeto WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
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
new ProjetoDAO();