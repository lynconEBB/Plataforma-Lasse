<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/styleGasto.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Gastos Gerais</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../../../assets/images/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">

        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../../../assets/images/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
        <?php foreach ($viagens as $viagem) { ?>
            <div class="container-viagem">
                <h3><?=$viagem->getOrigem()?> -> <?=$viagem->getDestino()?></h3>
            </div>
            <div class="container-gastos">
            <?php foreach ($viagem->getGastos() as $gasto){ ?>
                    <div class="gasto">
                        <h5><?=$gasto->getTipo()?></h5>
                        <h5><?=$gasto->getValor()?></h5>
                    </div>
            <?php
            }
            echo "</div>";
        }
        ?>
</main>

<script src="../Default/js/jquery.js"></script>
<script src="../Default/js/bootstrap.js"></script>
</body>
</html>
