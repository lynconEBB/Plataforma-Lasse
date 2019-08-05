<!-- Formulario de Viagens -->
<form id="form-geral" action="/acaoViagem" method="post">
    <div class="form-group">
        <label for="origem" class="col-form-label">Origem</label>
        <input class="form-control" id="origem" name="origem">
    </div>
    <div class="form-group">
        <label for="destino" class="col-form-label">Destino</label>
        <input class="form-control" id="destino" name="destino">
    </div>
    <div class="form-group">
        <label for="dtIda" class="col-form-label">Data de Ida</label>
        <input type="text" class="form-control" id="dtIda" name="dtIda">
    </div>
    <div class="form-group">
        <label for="dtVolta" class="col-form-label">Data de Volta</label>
        <input type="text" class="form-control" id="dtVolta" name="dtVolta">
    </div>
    <div class="form-group">
        <label for="justificativa" class="col-form-label">Justificativa</label>
        <input type="text" class="form-control" id="justificativa" name="justificativa">
    </div>
    <div class="form-group">
        <label for="observacoes" class="col-form-label">Observações</label>
        <input type="text" class="form-control" id="observacoes" name="observacoes">
    </div>
    <div class="form-group">
        <label for="passagem" class="col-form-label">Passagem</label>
        <input type="text" class="form-control" id="passagem" name="passagem">
    </div>
    <div class="form-group" id="group-idVeiculo">
        <label for="idVeiculo">Veiculo</label>
        <select class="custom-select" name="idVeiculo" id="idVeiculo">
            <option type="hidden" value="escolher"> Escolha um Veiculo</option>
            <option style="display:none;" value="novo">Novo</option>
            <?php

                foreach ($veiculos as $veiculo){
                    echo "<option value='{$veiculo->getId()}'>{$veiculo->getNome()}</option>";
                }
            ?>
        </select>
    </div>
    <button type="button" id="cadastra-veiculo">&plus;</button>


<!--********************************* Formulário de Veículos ***********************************-->
    <section id="form-veiculo">
        <div class="form-group">
            <label for="nome" class="col-form-label">Nome</label>
            <input class="form-control" id="nome" name="nomeVeiculo">
        </div>
        <div class="form-group">
            <label for="tipo" class="col-form-label">Tipo</label>
            <input class="form-control" id="tipo" name="tipoVeiculo">
        </div>
        <div class="form-group">
            <label for="dtRetirada" class="col-form-label">Data de Retirada</label>
            <input type="text" class="form-control" id="dtRetirada" name="dtRetirada">
        </div>
        <div class="form-group">
            <label for="dtDevolucao" class="col-form-label">Data de Devolução</label>
            <input type="text" class="form-control" id="dtDevolucao" name="dtDevolucao">
        </div>
        <div class="form-group">
            <label for="horarioRetirada" class="col-form-label">Horario de Retirada</label>
            <input type="text" class="form-control" id="horarioRetirada" name="horarioRetirada">
        </div>
        <div class="form-group">
            <label for="horarioDevolucao" class="col-form-label">Horário de Devolução</label>
            <input type="text" class="form-control" id="horarioDevolucao" name="horarioDevolucao">
        </div>
        <div class="form-group" id="group-idCondutor">
            <label for="idCondutor">Condutor</label>
            <select class="custom-select" name="idCondutor" id="idCondutor">
                <option type="hidden" value="escolher"> Escolha um Condutor</option>
                <option style="display:none;" value="novo"></option>
                <?php
                    foreach ($condutores as $condutor){
                        echo "<option value='{$condutor->getId()}'>{$condutor->getNome()}</option>";
                    }
                ?>
            </select>
        </div>
        <button type="button" id="cadastra-condutor">&plus;</button>
        <input type="hidden" name="id" id="id">
    </section>

    <!--*************************** Formulário de Condutores *************************-->
    <section id="form-condutor">
        <div class="form-group">
            <label for="nomeCondutor" class="col-form-label">Nome</label>
            <input class="form-control" id="nomeCondutor" name="nomeCondutor">
        </div>
        <div class="form-group">
            <label for="cnh" class="col-form-label">Número CNH</label>
            <input class="form-control" id="cnh" name="cnh">
        </div>
        <div class="form-group">
            <label for="validadeCNH" class="col-form-label">Data de Validade CNH</label>
            <input type="text" class="form-control" id="validadeCNH" name="validadeCNH">
        </div>
    </section>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="dtEntradaHosp" class="col-form-label">Data de Entrada da Hospedagem</label>
            <input type="text" class="form-control" id="dtEntradaHosp" name="dtEntradaHosp">
        </div>
        <div class="form-group col-sm-6">
            <label for="dtSaidaHosp" class="col-form-label">Data de Saida da Hospedagem</label>
            <input type="text" class="form-control" id="dtSaidaHosp" name="dtSaidaHosp">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="horaEntradaHosp" class="col-form-label">Horário de Entrada da Hospedagem</label>
            <input type="text" class="form-control" id="horaEntradaHosp" name="horaEntradaHosp">
        </div>
        <div class="form-group col-sm-6">
            <label for="horaSaidaHosp" class="col-form-label">Horário de Saida da Hospedagem</label>
            <input type="text" class="form-control" id="horaSaidaHosp" name="horaSaidaHosp">
        </div>
    </div>

    <!--*************************** Formulário de Gastos *************************-->
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Aluguel de Veiculos</label>
            <input type="hidden" name="gasto[1][nome]" value="Aluguel de Veiculos">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[1][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Combustível</label>
            <input type="hidden" name="gasto[2][nome]" value="Combustível">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[2][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Estacionamento</label>
            <input type="hidden" name="gasto[3][nome]" value="Estacionamento">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[3][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Passagens rodoviárias (metrô/ônibus)</label>
            <input type="hidden" name="gasto[4][nome]" value="Passagens rodoviárias (metrô/ônibus)">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[4][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Passagens rodoviárias internacionais</label>
            <input type="hidden" name="gasto[5][nome]" value="Passagens rodoviárias internacionais">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[5][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Pedágio</label>
            <input type="hidden" name="gasto[6][nome]" value="Pedágio">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[6][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Seguro internacional (obrigatório)</label>
            <input type="hidden" name="gasto[7][nome]" value="Seguro internacional (obrigatório)">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[7][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">Táxi</label>
            <input type="hidden" name="gasto[8][nome]" value="Taxí">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[8][valor]">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="AlugueldeVeiculos" class="col-form-label">outras despesas de locação de veículos</label>
            <input type="hidden" name="gasto[9][nome]" value="Outras despesas de locação de veículos">
        </div>
        <div class="form-group col-sm-6">
            <input type="text" class="form-control" id="AlugueldeVeiculos" name="gasto[9][valor]">
        </div>
    </div>

    <input type="hidden" name="acao" value="cadastrarViagem">
    <input type="hidden" name="idTarefa" value="<?= $_GET['idTarefa']?>">
    <input type="hidden" name="idFuncionario" value="<?= $_SESSION['usuario-id']?>">
    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>

</form>