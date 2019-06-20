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
            <?php if($viagem->getViajante()->getId() == $_SESSION['usuario-id']): ?>
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
            <?php endif; ?>
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

            </div>
        </div>
    </div>
</div>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesViagem.js"></script>
</body>
</html>

