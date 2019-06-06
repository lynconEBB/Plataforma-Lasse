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
        $comando = "DELETE FROM tbViagem WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindParam(':id',$id);
        $stm->execute();

    }

    public function listar(){
        $comando = "SELECT * from tbViagem";
        $stm = $this->pdo->prepare($comando);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $veiculoDAO = new VeiculoDao();
        $funcDAO = new UsuarioDao();
        $gastoDAO = new GastoDao();
        $viagens = array();

        foreach ($rows as $resul){
            $veiculo = $veiculoDAO->listarPorId($resul['idVeiculo']);
            $viajante = $funcDAO->listarPorId($resul['idUsuario']);
            $gastos = $gastoDAO->listarPorIdViagem($resul['id']);
            $obj = new ViagemModel($viajante,$veiculo,$resul['origem'],$resul['destino'],$resul['dataIda'],$resul['dataVolta'],$resul['passagem'],$resul['justificativa'],$resul['observacoes'],$resul['dataEntradaHosp'],$resul['dataSaidaHosp'],$resul['HorarioEntradaHosp'],$resul['HorarioSaidaHosp'],$resul['id'],$gastos);
            $viagens[] = $obj;
        }

        return $viagens;
    }

    function atualizar(ViagemModel $viagem){
        $comando = "UPDATE tbViagem SET origem = :origem, destino=:destino,dataIda=:dataIda, dataVolta=:dataVolta, justificativa=:justificativa, observacoes=:observacoes, passagem=:passagem,
                    idVeiculo=:idVeiculo, dataEntradaHosp=:dataEntradaHosp, dataSaidaHosp=:dataSaidaHosp, HorarioEntradaHosp=:HorarioEntradaHosp, HorarioSaidaHosp=:HorarioSaidaHosp,idUsuario=:idUsuario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':idVeiculo',$viagem->getVeiculo()->getId());
        $stm->bindValue(':id',$viagem->getId());
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

        $stm->execute();
    }

    public function listarPorIdTarefa($id){
        $comando = "SELECT * from tbViagem WHERE idTarefa = :id";
        $stm = $this->pdo->prepare($comando);

        $stm->bindValue(':id',$id);

        $stm->execute();
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        $veiculoDAO = new VeiculoDao();
        $funcDAO = new UsuarioDao();
        $gastoDAO = new GastoDao();
        $viagens = array();

        foreach ($rows as $resul){
            $veiculo = $veiculoDAO->listarPorId($resul['idVeiculo']);
            $viajante = $funcDAO->listarPorId($resul['idUsuario']);
            $gastos = $gastoDAO->listarPorIdViagem($resul['id']);
            $obj = new ViagemModel($viajante,$veiculo,$resul['origem'],$resul['destino'],$resul['dataIda'],$resul['dataVolta'],$resul['passagem'],$resul['justificativa'],$resul['observacoes'],$resul['dataEntradaHosp'],$resul['dataSaidaHosp'],$resul['HorarioEntradaHosp'],$resul['HorarioSaidaHosp'],$resul['id'],$gastos);
            $viagens[] = $obj;
        }

        return $viagens;

    }

}
