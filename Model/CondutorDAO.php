<?php
require_once 'CrudDAO.php';
require_once 'Condutor.php';

class CondutorDAO extends CrudDAO {

    //Insere Objeto Condutor no Banco de Dados
    function cadastrar(Condutor $condutor){
        $comando = "INSERT INTO tbcondutor (nome,cnh,validadeCNH) values (:nome, :cnh, :validadeCNH)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());

        $stm->execute();
        header('Location:../View/condutorView.php?success=true');
    }

    function excluir($id){
        $comando = "DELETE FROM tbcondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
        header('Location:../View/condutorView.php?success=true');
    }

    //Retorna todos os condutores em uma lista de objetos da classe modelo Condutor
    public function listar(){
        $comando = "SELECT * FROM tbcondutor";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new Condutor($row['nome'],$row['cnh'],$row['validadeCNH'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    function atualizar(Condutor $condutor){
        $atributos = [
            'nome'=>$condutor->getNome(),
            'cnh'=> $condutor->getCnh(),
            'validadeCNH'=>$condutor->getValidadeCNH(),
            'id'=>$condutor->getId()];

        $comando = "UPDATE tbcondutor SET nome=:nome,cnh=:cnh,validadeCNH=:validadeCNH WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':nome',$atributos['nome']);
        $stm->bindParam(':cnh',$atributos['cnh']);
        $stm->bindParam(':validadeCNH',$atributos['validadeCNH']);
        $stm->bindParam(':id',$atributos['id']);

        $stm->execute();
        header('Location:../View/condutorView.php?success=true');
    }


}

new CondutorDAO();