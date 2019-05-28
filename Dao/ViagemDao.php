<?php
require_once '../Services/Autoload.php';

class ViagemDao extends CrudDao {

    function cadastrar(ViagemModel $viagem, $idTarefa)
    {

        $comando = "INSERT INTO tbViagem (idVeiculo,idTarefa,origem,destino,dataIda,dataVolta,justificativa,observacoes,passagem,dataEntradaHosp,dataSaidaHosp,HorarioEntradaHosp,HorarioSaidaHosp,
idUsuario,totalGasto) values (:idVeiculo, :idTarefa, :origem, :destino, :dataIda, :dataVolta, :justificativa, :observacoes, :passagem, :dataEntradaHosp, :dataSaidaHosp, :HorarioEntradaHosp, :HorarioSaidaHosp,
:idUsuario,:totalGasto)";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':idVeiculo',$viagem->getVeiculo()->getId());
        $stm->bindValue(':idTarefa',$idTarefa);
        $stm->bindValue(':origem',$viagem->getOrigem());
        $stm->bindValue(':destino',$viagem->getDestino());
        $stm->bindValue(':dataIda',$viagem->getDtIda());
        $stm->bindValue(':dataVolta',$viagem->getDtVolta());
        $stm->bindValue(':justificativa',$viagem->getJustificativa());
        $stm->bindValue(':observacoes',$viagem->getObservacoes());
        $stm->bindValue(':passagem',$viagem->getPassagem());
        $stm->bindValue(':dataEntradaHosp',$viagem->getDtEntradaHosp());
        $stm->bindValue(':dataSaidaHosp',$viagem->getDtSaidaHosp());
        $stm->bindValue(':HorarioEntradaHosp',$viagem->getHoraEntradaHosp());
        $stm->bindValue(':HorarioSaidaHosp',$viagem->getHoraSaidaHosp());
        $stm->bindValue(':idUsuario',$viagem->getViajante()->getId());
        $stm->bindValue(':totalGasto',$viagem->getTotalGasto());

        $stm->execute();

    }

    function excluir($id)
    {
        /*$comando = "DELETE FROM tbcondutor WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();
        header('Location:../View/CondutorView.php?success=true');*/
    }

    //Retorna todos os condutores em uma lista de objetos da classe modelo CondutorModel
    public function listar(){
        /*$comando = "SELECT * FROM tbViagem";
        $stm = $this->pdo->prepare($comando);
        $stm->execute();
        $result =array();
        while($row = $stm->fetch(PDO::FETCH_ASSOC)){
            $obj = new ViagemModel($row['nome'],$row['cnh'],$row['validadeCNH']);
            $result[] = $obj;
        }
        return $result;*/
    }

    function atualizar(CondutorModel $condutor){
/*
        $comando = "UPDATE tbcondutor SET nome=:nome,cnh=:cnh,validadeCNH=:validadeCNH WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':nome',$condutor->getNome());
        $stm->bindValue(':cnh',$condutor->getCnh());
        $stm->bindValue(':validadeCNH',$condutor->getValidadeCNH());
        $stm->bindValue(':id',$condutor->getId());

        $stm->execute();
        header('Location:../View/CondutorView.php?success=true');*/
    }

    public function listarPorIdTarefa($id){
        $comando = "SELECT * from tbViagem WHERE idTarefa = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':id',$id);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $veiculoDAO = new VeiculoDao();
        $funcDAO = new FuncionarioDao();
        $viagens = array();

        foreach ($rows as $resul){
            $veiculo = $veiculoDAO->listarPorId($resul['idVeiculo']);
            $viajante = $funcDAO->listarPorId($resul['idUsuario']);
            $obj = new ViagemModel($viajante,$veiculo,$resul['origem'],$resul['destino'],$resul['dataIda'],$resul['dataVolta'],$resul['passagem'],$resul['justificativa'],$resul['observacoes'],$resul['dataEntradaHosp'],$resul['dataSaidaHosp'],$resul['HorarioEntradaHosp'],$resul['HorarioSaidaHosp'],'34');
            $viagens[] = $obj;
        }

        return $viagens;

    }
}
