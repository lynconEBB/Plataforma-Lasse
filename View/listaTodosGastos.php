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
        </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesGasto.js"></script>
</body>
</html>

