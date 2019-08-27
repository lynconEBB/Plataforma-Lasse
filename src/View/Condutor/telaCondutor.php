<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/styleCondutor.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Condutores</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-2)" title="Return to the previous page" class="side-bar-icon">
                <img src="../Default/img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">
        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../Default/img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <?php
    foreach ($condutores as $condutor):
        ?>
        <div class="container-condutor">
            <div class="container-foto">
                <img src="../Default/img/Icons/condutor.png" class="img-condutor">
            </div>
            <div class="container-content">
                <h3><b>Nome:</b> <?= $condutor->getNome() ?></h3>
                <h6><b>CNH:</b> <?= $condutor->getCnh() ?></h6>
                <h6><b>Validade CNH:</b> <?= $condutor->getValidadeCNH() ?></h6>
            </div>
        </div>
        <div class="acoes">
            <button class='btn-opcao' data-toggle='modal' data-target='#modalAlterar' data-id='<?= $condutor->getId()?>' data-nome='<?= $condutor->getNome()?>' data-cnh='<?= $condutor->getCnh()?>' data-val='<?= $condutor->getValidadeCNH()?>'>
                <img class="img-icon" src='../Default/img/Icons/editarIcone.png' alt=''>
            </button>
            <form action="/acaoCondutor" method="post">
                <input type="hidden" name="acao" value="excluirCondutor">
                <input type="hidden" name="id" value="<?= $condutor->getId()?>">
                <button class='btn-opcao'>
                    <img class="img-icon" src='../Default/img/Icons/lixeiraIcone.png'>
                </button>
            </form>
        </div>
    <?php
    endforeach;
    ?>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="../Default/img/Icons/adicionar.png" class="img-icon">
</button>
<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Condutores</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoCondutor" method="post">
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
                    <input type="hidden" name="acao" value="cadastrarCondutor">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Condutores</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoCondutor" method="post">
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
                    <input type="hidden" name="acao" value="alterarCondutor">
                    <input type="hidden" id="id" name="id">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../Default/js/jquery.js"></script>
<script src="../Default/js/bootstrap.js"></script>
<script src="../Default/js/funcoesCondutor.js"></script>
</body>
</html>

