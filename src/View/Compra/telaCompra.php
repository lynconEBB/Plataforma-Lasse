<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/styleCompra.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<header class="page-header">
    <span class="titulo-header">Compras da Tarefa</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../../../assets/images/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">
            <a href="/menu/item" class="side-bar-icon">
                <img src="../../../assets/images/Icons/itemBranco.png" class="img-icon" alt="Menu de Itens">
            </a>
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
    <div class="container-compras">
        <header class="header-row">
            <h3>Proposito</h3>
            <h3>Total Gasto</h3>
            <h3>Comprador</h3>
        </header>
    <?php
    foreach ($compras as $compra):
        ?>
            <div class="container-row">
                <div class="row-content">
                    <h5><?=$compra->getProposito()?></h5>
                    <h5><?=$compra->getTotalGasto()?></h5>
                    <h5><?=$compra->getComprador()->getLogin()?></h5>
                </div>
                <div class="row-options">
                    <?php if($compra->getComprador()->getId() == $_SESSION['usuario-id']): ?>

                    <button class='btn-opcao' data-toggle='modal' data-target="#modalAlterar" data-id="<?=$compra->getId()?>" data-proposito="<?=$compra->getProposito()?>"
                            data-idtarefa="<?=$_GET['idTarefa']?>"  >
                        <img class="img-icon" src='../../../assets/images/Icons/editarIcone.png' alt=''>
                    </button>
                    <form action="/acaoCompra" method="post">
                        <input type="hidden" name="acao" value="excluirCompra">
                        <input type="hidden" name="id" value="<?php echo $compra->getId()?>">
                        <input type="hidden" name="idTarefa" value="<?php echo $_GET['idTarefa']?>">
                        <button class="btn-opcao">
                            <img class="img-icon" src='../../../assets/images/Icons/lixeiraIcone.png' alt=''>
                        </button>
                    </form>
                    <button type="button" class="btn-opcao">
                        <a href="/menu/compra/item?idCompra=<?=$compra->getId()?>">
                            <img class="img-icon" src='../../../assets/images/Icons/item.png' alt=''>
                        </a>
                    </button>
                    <?php endif;?>
                </div>
            </div>
    <?php endforeach; ?>
    </div>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="../../../assets/images/Icons/adicionar.png" class="img-icon">
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Compras</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoCompra" method="post">
                    <div class="form-group">
                        <label for="proposito" class="col-form-label">Proposito da Compra</label>
                        <input class="form-control" id="proposito" name="proposito">
                    </div>
                    <div class="form-group" id="itensCadastro">
                        <div id="container-itens"></div>
                    </div>

                    <button style="display: block;" class="btn" type="button" id="adicionar-item">&plus;</button>
                    <input type="hidden" name="idTarefa" value="<?=$_GET['idTarefa'];?>">
                    <input type="hidden" name="acao" value="cadastrarCompra">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlterar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Itens</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoCompra" method="post">
                    <div class="form-group">
                        <label for="proposito" class="col-form-label">Proposito da Compra</label>
                        <input class="form-control" id="proposito" name="proposito">
                    </div>
                    <div class="form-group" id="itensAlterar">
                        <div id="container-Itens"></div>
                    </div>
                    <div class="form-group">
                        <label for="idTarefa">Tarefa Pertencente</label>
                        <select class="custom-select" name="idTarefa" id="idTarefa">
                            <?php
                            foreach ($tarefas as $tarefa){
                                echo "<option value='{$tarefa->getId()}'>{$tarefa->getNome()}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="idTarefaAntiga" id="idTarefaAntiga">
                    <input type="hidden" name="acao" value="alterarCompra">
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../Default/js/jquery.js"></script>
<script src="../Default/js/bootstrap.js"></script>
<script src="../Default/js/funcoesCompra.js"></script>
</body>
</html>