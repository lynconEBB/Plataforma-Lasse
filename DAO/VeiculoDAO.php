<?php
require_once 'CondutorDAO.php';

class VeiculoDAO extends CrudDAO {

    function cadastrar(Veiculo $veiculo){
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
        header('Location:../View/veiculoView.php?success=true');
    }

    //Exclui Veiculo com base no id recebido
    function excluir($id){
        $comando = "DELETE FROM tbVeiculo WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
        header('Location:../View/veiculoView.php?success=true');
    }

    //Retorna todos os condutores em uma lista de objetos da classe modelo Condutor
    public function listar(){
        $comando = "SELECT * FROM tbVeiculo";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        $condutorDAO = new CondutorDAO();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $condutor = $condutorDAO->listarPorId($row['idCondutor']);
            $obj = new Veiculo($row['nome'],$row['tipo'],$row['dataRetirada'],$row['dataDevolucao'],$row['horarioRetirada'],$row['horarioDevolucao'],$condutor,$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(Veiculo $veiculo){

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
        header('Location:../View/veiculoView.php?success=true');
    }
}