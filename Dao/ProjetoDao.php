<?php

class ProjetoDao extends CrudDao {

    function cadastrar(ProjetoModel $projeto){
        $comando1 = "INSERT INTO tbProjeto (nome,descricao,dataFinalizacao,dataInicio) values (:nome, :descr, :dtFim,:dtInicio)";
        $stm = $this->pdo->prepare($comando1);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtFim',$projeto->getDataFinalizacao());
        $stm->bindValue(':dtInicio',$projeto->getDataInicio());

        $stm->execute();

        $comando2 = 'INSERT INTO tbUsuarioProjeto (idProjeto,idUsuario,dono) values (:idProjeto, :idUsuario,TRUE)';
        $stm = $this->pdo->prepare($comando2);

        $stm->bindValue(':idProjeto',$this->pdo->lastInsertId());
        $stm->bindValue(':idUsuario',$_SESSION['usuario-id']);

        $stm->execute();
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

    }

    function listar(){
        $comando = "SELECT * FROM tbProjeto";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new ProjetoModel($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id']);
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
        $projeto = new ProjetoModel($row['dataFinalizacao'],$row['dataInicio'],$row['descricao'],$row['nome'],$row['id']);
        return $projeto;
    }

    function alterar(ProjetoModel $projeto){

        $comando = "UPDATE tbProjeto SET nome=:nome,descricao=:descr,dataFinalizacao=:dtfim, dataInicio=:dtini WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$projeto->getNome());
        $stm->bindValue(':descr',$projeto->getDescricao());
        $stm->bindValue(':dtfim',$projeto->getDataFinalizacao());
        $stm->bindValue(':dtini',$projeto->getDataInicio());
        $stm->bindValue(':id',$projeto->getId());

        $stm->execute();
    }

    function procuraFuncionario($idProjeto,$idUsuario){
        $comando = "select * from tbUsuarioProjeto where idProjeto = :idProjeto and idUsuario = :idUsuario";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$idUsuario);

        $stm->execute();
        return $stm->rowCount();
    }

    function verificaDono($idProjeto)
    {
        $comando = "select * from tbUsuarioProjeto where idProjeto = :idProjeto and idUsuario = :idUsuario and dono = TRUE";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$_SESSION['usuario-id']);

        $stm->execute();
        return $stm->rowCount();
    }

    function adicionarFuncionario($idUsuario,$idProjeto)
    {
        $comando = "insert into tbUsuarioProjeto (idProjeto,idUsuario,dono) values (:idProjeto,:idUsuario,FALSE)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':idProjeto',$idProjeto);
        $stm->bindParam('idUsuario',$idUsuario);

        $stm->execute();
    }
}
