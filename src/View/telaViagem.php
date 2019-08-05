<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/styleViagem.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<header class="page-header">
    <span class="titulo-header">Viagens da Tarefa</span>
    <?php \Lasse\LPM\Services\Mensagem::exibir('danger'); ?>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="/img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">
            <a href="/menu/veiculo" class="side-bar-icon">
                <img src="/img/Icons/veiculo.png" class="img-icon" alt="Menu de Veiculos">
            </a>
            <a href="/menu/gasto" class="side-bar-icon">
                <img src="/img/Icons/gastoTodos.png" class="img-icon" alt="Menu de todos os Gastos">
            </a>
        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="/img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <div class="container-viagem">

        <?php foreach ($viagens as $viagem): ?>
            <div class="container-row">
                <h4 class="row-header">Viajante</h4>
                <h4 class="row-header">Viagem</h4>
                <h4 class="row-header">Veiculo</h4>
                <h4 class="row-header">Data Saída</h4>
                <h4 class="row-header">Total Gasto</h4>

                <div class="cell">
                    <span class="cell-detail"><?=$viagem->getViajante()->getNomeCompleto()?></span>
                </div>
                <div class="cell">
                    <span class="cell-detail"><?=$viagem->getOrigem()?>-><?=$viagem->getDestino()?></span>
                </div>
                <div class="cell">
                    <span class="cell-detail"><?=$viagem->getVeiculo()->getNome()?></span>
                </div>
                <div class="cell">
                    <span class="cell-detail"><?=$viagem->getDtIda()?></span>
                </div>
                <div class="cell">
                    <span class="cell-detail"><?=$viagem->getTotalGasto()?></span>
                </div>
                <?php if($viagem->getViajante()->getId() == $_SESSION['usuario-id']): ?>
                <div class="acoes-viagem">
                    <button class='btn-opcao' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$viagem->getId()?>' data-origem="<?=$viagem->getOrigem()?>" data-destino="<?=$viagem->getDestino()?>" data-dtida="<?=$viagem->getDtIda()?>"
                            data-dtvolta="<?=$viagem->getDtVolta()?>" data-justificativa="<?=$viagem->getJustificativa()?>" data-observacoes="<?=$viagem->getObservacoes()?>" data-passagem="<?=$viagem->getPassagem()?>" data-idveiculo="<?=$viagem->getVeiculo()->getId()?>"
                            data-dataentrahosp="<?=$viagem->getDtEntradaHosp()?>" data-datasaidahosp="<?=$viagem->getDtSaidaHosp()?>" data-horaentrahosp="<?=$viagem->getHoraEntradaHosp()?>" data-horasaidahosp="<?=$viagem->getHoraSaidaHosp()?>">
                        <img class="img-icon" src='/img/Icons/editarIcone.png' alt=''>
                    </button>
                    <form action="/acaoViagem" method="post">
                        <input type="hidden" name="acao" value="excluirViagem">
                        <input type="hidden" name="id" value="<?php echo $viagem->getId()?>">
                        <input type="hidden" name="idTarefa" value="<?=$_GET['idTarefa']?>">
                        <button class="btn-opcao">
                            <img class="img-icon" src='/img/Icons/lixeiraIcone.png' alt=''>
                        </button>
                    </form>
                </div>
                <div class="acoes-gastos">
                    <a href="/menu/viagem/gastos?idViagem=<?=$viagem->getId()?>">
                        <button class="btn-opcao">
                            <img class="img-icon" src='/img/Icons/gasto.png' alt=''>
                        </button>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="/img/Icons/adicionar.png" class="img-icon">
</button>

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
                <?php require 'formViagemAlterar.php'; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php require 'formViagemCadastro.php'; ?>
            </div>
        </div>
    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/funcoesViagem.js"></script>
</body>
</html>