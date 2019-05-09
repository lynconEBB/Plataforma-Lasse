<?php

require_once '../vendor/autoload.php';
include '../vendor/cybermonde/odtphp/library/Odf.php';

class ViagemControl{
    function __construct(){
        if(isset($_REQUEST['acao'])){
            $this->decideAcao($_REQUEST['acao']);
        }
    }
    public function decideAcao($acao){
        switch ($acao){
            case 1: $this->gerarDocumento();
        }
    }
    public function gerarDocumento(){
        $odf = new Odf("formularioViagem.odt");
        $odf->setVars('Passaporte)','Passaporte): '.$_POST['nome']);
        $odf->setVars('RG','RG: '.$_POST['rg']);
        $dataEmissao =  explode('-',$_POST['dataEm']);
        $odf->setVars('Data emissão','Data emissão: '.$dataEmissao[2].'/'.$dataEmissao[1].'/'.$dataEmissao[0]);
        $odf->setVars('CPF','CPF: '.$_POST['cpf']);
        $dataNascimento =  explode('-',$_POST['dtNascimento']);
        $odf->setVars('Data nascimento','Data nascimento: '.$dataNascimento[2].'/'.$dataNascimento[1].'/'.$dataNascimento[0]);
        $odf->setVars2(' '.$_POST['tipo'],'X ) '.$_POST['tipo']);
        $odf->setVars2(' '.$_POST['proposito'],'X ) '.$_POST['proposito']);

        $odf->setVars('Convênio','Convênio: '.$_POST['nomeNumCon']);
        $odf->setVars('custo','custo: '.$_POST['NumCentro']);
        $odf->setVars('Atividade','Atividade: '.$_POST['atividade']);
        $odf->setVars('recurso','recurso: '.$_POST['fonteRecurso']);
        $odf->setVars('rege a viagem','rege a viagem: '.$_POST['metaViagem']);

        $odf->setVars('Origem','Origem: '.$_POST['origem']);
        $dataIda = explode('-',$_POST['dtIda']);
        $odf->setVars('Data ida','Data ida: '.$dataIda[2].'/'.$dataIda[1].'/'.$dataIda[0]);
        $odf->setVars('Destino','Destino: '.$_POST['destino']);
        $dataVolta = explode('-',$_POST['dtVolta']);
        $odf->setVars('volta','volta: '.$dataVolta[2].'/'.$dataVolta[1].'/'.$dataVolta[0]);

        $odf->setVars('detalhada da viagem','detalhada da viagem: '.$_POST['justificativa']);
        $odf->setVars('Observações','Observações: '.$_POST['observacoes']);

        $odf->setVars('Passagem','Passagem: '.$_POST['passagem']);
        $odf->setVars2(' '.$_POST['tipoPassagem'],'X ) '.$_POST['tipoPassagem']);
        $odf->setVars('Veículo','Veículo: '.$_POST['veiculo']);
        $odf->setVars2(' '.$_POST['tipoVeiculo'],'X ) '.$_POST['tipoVeiculo']);
        $odf->setVars('condutor','condutor: '.$_POST['condutor']);
        $odf->setVars('CNH','CNH: '.$_POST['cnh']);
        $odf->setVars('Validade','Validade: '.$_POST['validade']);
        $odf->setVars('Categoria','Categoria: '.$_POST['categoria']);
        $dataRetirada = explode('-',$_POST['dtRetirada']);
        $odf->setVars('retirada do veículo na agência','retirada do veículo na agência: '.$dataRetirada[2].'/'.$dataRetirada[1].'/'.$dataRetirada[0]);
        $dataDevolucao = explode('-',$_POST['dtDevolucao']);
        $odf->setVars('devolução do veículo na agência','devolução do veículo na agência: '.$dataDevolucao[2].'/'.$dataDevolucao[1].'/'.$dataDevolucao[0]);
        $odf->setVars('de entrada','de entrada: '.$_POST['horarioEntrada']);
        $dataEntrada = explode('-',$_POST['dtEntrada']);
        $odf->setVars('dtEntrada',$dataEntrada[2].'/'.$dataEntrada[1].'/'.$dataEntrada[0]);
        $dataSaida = explode('-',$_POST['dtSaida']);
        $odf->setVars('dtSaida',$dataSaida[2].'/'.$dataSaida[1].'/'.$dataSaida[0]);
        $odf->setVars('de saída','de saída: '.$_POST['horarioSaida']);

        $odf->setVars('Aluguel','R$ '.$_POST['precoAluguel']);
        $odf->setVars('Combustivel','R$ '.$_POST['precoCombustivel']);
        $odf->setVars('Estacionamento','R$ '.$_POST['precoEstacionamento']);
        $odf->setVars('PassagemRodoOni','R$ '.$_POST['precoPassagemNacional']);
        $odf->setVars('PassagemRodIn','R$ '.$_POST['precoPassagemInternacional']);
        $odf->setVars('Pedagio','R$ '.$_POST['precoPedagio']);
        $odf->setVars('Seguro','R$ '.$_POST['precoSeguro']);
        $odf->setVars('Taxi','R$ '.$_POST['precoTaxi']);
        $odf->setVars('Outros','R$ '.$_POST['precoOutros']);

        $odf->exportAsAttachedFile('ola.odt');
    }

}
new ViagemControl();

