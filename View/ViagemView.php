<html>
<head>
    <title>Lasse - PTI</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/estiloViagemCadastro.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<table class="table table-hover">
    <thead>
        <th>Nome Viajante</th>
        <th>Veiculo</th>
        <th>Condutor</th>
        <th>Origem</th>
        <th>Destino</th>
        <th>Justificativa</th>
        <th>Observacoes</th>
        <th>Total Gasto</th>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        foreach ($viagens as $viagem) {
        ?>
        <tr>
            <td><?=$viagem->getViajante()->getNomeCompleto()?></td>
            <td><?=$viagem->getVeiculo()->getNome()?></td>
            <td><?=$viagem->getVeiculo()->getCondutor()->getNome()?></td>
            <td><?=$viagem->getOrigem()?></td>
            <td><?=$viagem->getDestino()?></td>
            <td><?=$viagem->getJustificativa()?></td>
            <td><?=$viagem->getObservacoes()?></td>
            <td><?=$viagem->getTotalGasto()?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$viagem->getId()?>' data-origem="<?=$viagem->getOrigem()?>" data-destino="<?=$viagem->getDestino()?>" data-dtida="<?=$viagem->getDtIda()?>"
                data-dtvolta="<?=$viagem->getDtVolta()?>" data-justificativa="<?=$viagem->getJustificativa()?>" data-observacoes="<?=$viagem->getObservacoes()?>" data-passagem="<?=$viagem->getPassagem()?>" data-idveiculo="<?=$viagem->getVeiculo()->getId()?>"
                data-dataentrahosp="<?=$viagem->getDtEntradaHosp()?>" data-datasaidahosp="<?=$viagem->getDtSaidaHosp()?>" data-horaentrahosp="<?=$viagem->getHoraEntradaHosp()?>" data-horasaidahosp="<?=$viagem->getHoraSaidaHosp()?>">
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="/acaoViagem" method="post">
                    <input type="hidden" name="acao" value="excluirViagem">
                    <input type="hidden" name="id" value="<?php echo $viagem->getId()?>">
                    <input type="hidden" name="idTarefa" value="<?=$_GET['idTarefa']?>">
                    <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                </form>
            </td>
            <td>
                <a href="/menu/viagem/gastos?idViagem=<?=$viagem->getId()?>"><button class="btn"><img width='20' src='../img/money-bill-alt-solid.svg' alt=''></button></a>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<button onclick="location.href='/menu/viagem/cadastro?idTarefa=<?= $_REQUEST['idTarefa']?>'" type="button" class="btn btn-primary">
    Cadastrar Nova Viagem
</button>

<a href="/menu/veiculo"><button type="button" class="btn btn-warning">Menu de Veiculos</button></a>
<a href="/menu/gasto"><button type="button" class="btn btn-warning">Menu de Gastos</button></a>

<div class="modal fade" id="modalAlterar" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                            $veiculoControl = new VeiculoControl();
                            $veiculos = $veiculoControl->listar();
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
                                <option style="display:none;" value="novo"></option>
                                <?php
                                $condutorControl = new CondutorControl();
                                $condutores = $condutorControl->listar();
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

                    <input type="hidden" name="acao" value="alterarViagem">
                    <input type="hidden" name="idViagem" id="idViagem">
                    <input type="hidden" name="idFuncionario" value="<?= $_SESSION['usuario-id']?>">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesViagem.js"></script>
</body>
</html>

