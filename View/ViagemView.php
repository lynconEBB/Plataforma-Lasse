<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Services/Autoload.php';
LoginControl::verificar();

$viagemControl = new ViagemControl();
$viagemControl->verificaPermissao();

$resul = $viagemControl->listarPorIdTarefa($_GET['idTarefa']);

?>
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
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <?php
        foreach ($resul as $obj) {
        ?>
        <tr>
            <td><?=$obj->getViajante()->getNomeCompleto()?></td>
            <td><?=$obj->getVeiculo()->getNome()?></td>
            <td><?=$obj->getVeiculo()->getCondutor()->getNome()?></td>
            <td><?=$obj->getOrigem()?></td>
            <td><?=$obj->getDestino()?></td>
            <td><?=$obj->getJustificativa()?></td>
            <td><?=$obj->getObservacoes()?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$obj->getId()?>' data-origem="<?=$obj->getOrigem()?>" data-destino="<?=$obj->getDestino()?>" data-dtida="<?=$obj->getDtIda()?>"
                data-dtvolta="<?=$obj->getDtVolta()?>" data-justificativa="<?=$obj->getJustificativa()?>" data-observacoes="<?=$obj->getObservacoes()?>" data-passagem="<?=$obj->getPassagem()?>" data-idveiculo="<?=$obj->getVeiculo()->getId()?>"
                data-dataentrahosp="<?=$obj->getDtEntradaHosp()?>" data-datasaidahosp="<?=$obj->getDtSaidaHosp()?>" data-horaentrahosp="<?=$obj->getHoraEntradaHosp()?>" data-horasaidahosp="<?=$obj->getHoraSaidaHosp()?>"
                data-gasto1 ="<?=$obj->getGastos()[0]->getValor()?>" data-gasto2 ="<?=$obj->getGastos()[1]->getValor()?>" data-gasto3 ="<?=$obj->getGastos()[2]->getValor()?>" data-gasto4 ="<?=$obj->getGastos()[3]->getValor()?>"
                data-gasto5 ="<?=$obj->getGastos()[4]->getValor()?>" data-gasto6 ="<?=$obj->getGastos()[5]->getValor()?>" data-gasto7 ="<?=$obj->getGastos()[6]->getValor()?>" data-gasto8 ="<?=$obj->getGastos()[7]->getValor()?>"
                        data-gasto9 ="<?=$obj->getGastos()[8]->getValor()?>">
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="../Control/ViagemControl.php" method="post">
                    <input type="hidden" name="acao" value="2">
                    <input type="hidden" name="id" value="<?php echo $obj->getId()?>">
                    <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                </form>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<button onclick="location.href='ViagemCadastroView.php?idTarefa=<?= $_REQUEST['idTarefa']?>'" type="button" class="btn btn-primary">
    Cadastrar Nova Viagem
</button>

<a href="VeiculoView.php"><button type="button" class="btn btn-warning">Menu de Veiculos</button></a>

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
                <form id="form-geral" action="../Control/ViagemControl.php" method="post">
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

