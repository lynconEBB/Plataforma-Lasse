<?php

require_once '../Services/Autoload.php';
LoginControl::verificar();

$viagemControl = new ViagemControl();
$viagens = $viagemControl->listar();

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
    <th>Viagem</th>
    <th>Tipo</th>
    <th>Valor</th>
    <th></th>

    </thead>
    <tbody>
    <?php

    foreach ($viagens as $viagem) {
        foreach ($viagem->getGastos() as $gasto){
        ?>
        <tr>
            <td><?=$viagem->getOrigem()?> -> <?=$viagem->getDestino()?></td>
            <td><?=$gasto->getTipo()?></td>
            <td><?=$gasto->getValor()?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$gasto->getId()?>' data-tipo="<?=$gasto->getTipo()?>" data-valor="<?=$gasto->getValor()?>">
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
        </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>

<div class="modal fade" id="modalAlterar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Gasto</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Control/GastoControl.php" method="post">
                    <div class="form-group">
                        <label for="tipoGasto" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipoGasto" name="tipoGasto">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <input type="hidden" name="acao" value="3">
                    <input type="hidden" id="id" name="id">
                    <button type="submit" class="btn btn-primary align-self-center">Alterar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesGasto.js"></script>
</body>
</html>

