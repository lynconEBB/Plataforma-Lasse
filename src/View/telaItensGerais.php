<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleItem.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Itens Gerais</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">

        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <?php foreach ($compras as $compra) { ?>
    <div class="container-compra">
        <h3><?=$compra->getProposito()?></h3>
    </div>
    <div class="container-itens">
        <?php foreach ($compra->getItens() as $item){ ?>
            <div class="item">
                <h5><?= $item->getNome() ?></h5>
                <h5><?= $item->getValor() ?></h5>
                <h5><?= $item->getQuantidade() ?></h5>
                <h5><?= $item->getValorParcial() ?></h5>
            </div>
            <?php
        }
        echo "</div>";
        }
        ?>
</main>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>
