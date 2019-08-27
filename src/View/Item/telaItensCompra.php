<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/styleItem.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Itens da Compra</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../../img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">

        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../../img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <div class="container-itens compra">
        <?php foreach ($itens as $item): ?>
            <div class="item-compra">
                <div class="cell">
                    <h3>Nome</h3>
                    <h5><?= $item->getNome() ?></h5>
                </div>
                <div class="cell">
                    <h3>Valor Unitário</h3>
                    <h5><?= $item->getValor() ?></h5>
                </div>
                <div class="cell">
                    <h3>Quantidade</h3>
                    <h5><?= $item->getQuantidade() ?></h5>
                </div>
                <div class="cell">
                    <h3>Valor Parcial</h3>
                    <h5><?= $item->getValorParcial() ?></h5>
                </div>
                <button class='btn-opcao' data-toggle='modal' data-target='#modalAlterar' data-id='<?= $item->getId() ?>'
                        data-nome='<?= $item->getNome() ?>'
                        data-valor='<?= $item->getValor() ?>' data-qtd="<?= $item->getQuantidade() ?>">
                    <img class="img-icon" src='../../img/Icons/editarIcone.png' alt=''>
                </button>
                <form action="/acaoItem" method="post">
                    <input type="hidden" name="acao" value="excluirItem">
                    <input type="hidden" name="id" value="<?= $item->getId() ?>">
                    <input type="hidden" name="idCompra" value="<?= $_GET['idCompra'] ?>">
                    <button class="btn-opcao">
                        <img class="img-icon" src='../../img/Icons/lixeiraicone.png' alt=''>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="../../img/Icons/adicionar.png" class="img-icon" alt="Botao para cadastrar">
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Itens</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoItem" method="post">
                    <div class="form-group">
                        <label for="nome" class="col-form-label">Nome</label>
                        <input class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <div class="form-group">
                        <label for="qtd" class="col-form-label">Quantidade</label>
                        <input type="text" class="form-control" id="qtd" name="qtd">
                    </div>
                    <input type="hidden" name="acao" value="cadastrarItem">
                    <input type="hidden" name="idCompra" value="<?= $_GET['idCompra'] ?>">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlterar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Itens</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoItem" method="post">
                    <div class="form-group">
                        <label for="nome" class="col-form-label">Nome</label>
                        <input class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <div class="form-group">
                        <label for="qtd" class="col-form-label">Quantidade</label>
                        <input type="text" class="form-control" id="qtd" name="qtd">
                    </div>
                    <input type="hidden" name="acao" value="alterarItem">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="idCompra" value="<?= $_GET['idCompra'] ?>">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/funcoesItem.js"></script>
</body>
</html>
