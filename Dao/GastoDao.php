<?php

require_once '../Services/Autoload.php';

class GastoDao extends CrudDao
{
    //Insere Objeto CondutorModel no Banco de Dados
    function cadastrar(CondutorModel $condutor){
        /*$comando = "INSERT INTO tbCondutor (nome,cnh,validadeCNH) values (:nome, :cnh, :validadeCNH)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());

        $stm->execute();*/
    }

    function cadastrarGastos($gastos,$idViagem){
        $comando = "insert into tbGasto (tipo,valor,idViagem) values ";
        $parametros = array();
        foreach ($gastos as $gasto){
            $comando .= '(?,?,?),';
            $parametros[] = $gasto->getTipo();
            $parametros[] = $gasto->getValor();
            $parametros[] = $idViagem;
        }

        $comando = trim($comando,',');
        $stm = $this->pdo->prepare($comando);
        $stm->execute($parametros);
    }

    function excluir($id){
        /*$comando = "DELETE FROM tbCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
        header('Location:../View/CondutorView.php?success=true');*/
    }

    //Retorna todos os condutores em uma lista de objetos da classe modelo CondutorModel
    public function listarPorIdViagem($idViagem){
        $comando = "SELECT * FROM tbGasto WHERE idViagem= :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(':id',$idViagem);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new GastoModel($row['valor'],$row['tipo'],$row['id']);
            $result[] = $obj;
        }
        return $result;
    }

    public function listar(){

    }

    function atualizar(CondutorModel $condutor){
/*
        $comando = "UPDATE tbCondutor SET nome=:nome,cnh=:cnh,validadeCNH=:validadeCNH WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());
        $stm->bindValue(':id',$condutor->getId());

        $stm->execute();
        header('Location:../View/CondutorView.php?success=true');*/
    }

    public function listarPorId($id){
       /* $comando = "SELECT * from tbCondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':id',$id);

        $stm->execute();
        $resul = $stm->fetch(PDO::FETCH_ASSOC);
        $obj = new CondutorModel($resul['nome'],$resul['cnh'],$resul['validadeCNH'],$resul['id']);

        return $obj;*/
    }
}