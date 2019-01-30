<?php
include "cabecalho.php";
?>
    <form action="../Control/ViagemControl.php" method="post">
        <fieldset>
            Nome Completo:<input type="text" name="nome"><br>
            RG:<input type="text" name="rg"><br>
            Data de Emissão: <input type="date" name="dataEm"><br>
            CPF: <input type="text" name="cpf"><br>
            Data de Nascimento <input type="date" name="dtNascimento"><br>
            <input type="radio" name="tipo" value="Colaborador">Colaborador     <input type="radio" name="tipo" value="Terceiros">Terceiros       <input type="radio" name="tipo" value="Bolsista/Voluntário">Bolsista/Voluntário<br>
            <input type="radio" name="proposito" value="Viagem a">Trabalho     <input type="radio" name="proposito" value="Evento/Congresso">Evento/Congresso       <input type="radio" name="proposito" value="Viagem trei">Treinamento/Aprimoramento<br>
        </fieldset><br>
        <fieldset>
            Nome e Número Convênio: <input type="text" name="nomeNumCon"><br>
            Número Centro de Custo: <input type="text" name="NumCentro"><br>
            Atividade: <input type="text" name="atividade"><br>
            Fonte de Recurso: <input type="text" name="fonteRecurso"><br>
            Meta que rege a viagem: <input type="text" name="metaViagem"><br>
        </fieldset><br>
        <fieldset>
            Origem: <input type="text" name="origem"><br>
            Destino: <input type="text" name="destino"><br>
            Data Ida: <input type="date" name="dtIda"><br>
            Data Volta: <input type="date" name="dtVolta"><br>
        </fieldset><br>
        <fieldset>
            Justificativa detalhada:<br> <textarea rows="7" cols="30" name="justificativa"></textarea><br>
            Observações: <br><textarea rows="7" cols="30" name="observacoes"></textarea><br>
        </fieldset><br>
        <fieldset>
            Passagem:<input type="text" name="passagem"><br>
            <input type="radio" name="tipoPassagem" value="Aérea nacional (com">Aérea nacinonal(com franquia de bagagem)
            <input type="radio" name="tipoPassagem" value="Aérea nacional (sem">Aérea Nacional(sem franquia de bagagem)
            <input type="radio" name="tipoPassagem" value="Aérea internacional">Aérea Internacional
            <input type="radio" name="tipoPassagem" value="Terrestre nacional">Terrestre Nacional
            <input type="radio" name="tipoPassagem" value="Terrestre internacional">Terrestre Internacional<br>
        </fieldset><br>
        <fieldset>
            Veículo:<input type="text" name="veiculo"><br>
            <input type="radio" name="tipoVeiculo" value="Veículo locado">Veículo Locado
            <input type="radio" name="tipoVeiculo" value="Transporte">Transporte com parceiros/terceiros
            <input type="radio" name="tipoVeiculo" value="Veículo Fundação PTI-BR">Veículo Fundação PTI-BR<br>
            Nome do Condutor:<input type="text" name="condutor"><br>
            Número da CNH: <input type="text" name="cnh"><br>
            Validade: <input type="text" name="validade"><br>
            Categoria: <input type="text" name="categoria"><br>
            Data/Hora retirada do Veículo: <input type="text" name="dtRetirada"><br>
            Data/Hora devolução di Veículo: <input type="text" name="dtDevolucao">
        </fieldset><br>
        <fieldset>
            Data de Entrada: <input type="date" name="dtEntrada"><br>
            Horario de Entrada: <input type="text" name="horarioEntrada"><br>
            Data de Saída: <input type="date" name="dtSaida"><br>
            Horario de Saída: <input type="text" name="horarioSaida"><br>
        </fieldset><br>
        <fieldset>
            Aluguel de Veículo(locado fora de Foz): <input type="text" name="precoAluguel"><br>
            Combustível: <input type="text" name="precoCombustivel"><br>
            Estacionamento: <input type="text" name="precoEstacionamento"><br>
            Passagens Rodoviárias (metrô/ônibus): <input type="text" name="precoPassagemNacional"><br>
            Passagens Rodoviárias Internacionais: <input type="text" name="precoPassagemInternacional"><br>
            Pedágio: <input type="text" name="precoPedagio"><br>
            Seguro Internacional (obrigatório): <input type="text" name="precoSeguro"><br>
            Táxi: <input type="text" name="precoTaxi"><br>
            Outras Despesas: <input type="text" name="precoOutros"><br>
        </fieldset>
        <input type="hidden" name="acao" value="1">
        <input type="submit" value="Fazer Formulário">
    </form>
<?php
include "rodape.php";
?>
