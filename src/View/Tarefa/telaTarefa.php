<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/styleTarefa.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<header class="page-header">
    <span class="titulo-header">Tarefas do Projeto</span>
    <?php \Lasse\LPM\Services\Mensagem::exibir('danger');?>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="/menu/projeto" class="side-bar-icon">
                <img src="/View/img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">

        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="/View/img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <div class="container-tarefa">
        <div class="container-header-row">
            <h4 class="header-text">Nome</h4>
            <h4 class="header-text">Estado</h4>
            <h4 class="header-text">Início</h4>
            <h4 class="header-text">Conclusao</h4>
            <h4 class="header-text">Gastos</h4>
        </div>

    <?php foreach ($tarefas as $tarefa): ?>
        <div class="container-row">
            <div class="cell">
                <span class="cell-detail"><?= $tarefa->getNome() ?></span>
            </div>
            <div class="cell">
                <span class="cell-detail"><?= $tarefa->getEstado() ?></span>
            </div>
            <div class="cell">
                <span class="cell-detail"><?= $tarefa->getDataInicio()->format('d/m/Y') ?></span>
            </div>
            <div class="cell">
                <span class="cell-detail"><?= $tarefa->getDataConclusao()->format('d/m/Y') ?></span>
            </div>
            <div class="cell">
                <span class="cell-detail"><?= $tarefa->getTotalGasto() ?></span>
            </div>
            <footer class="row-footer">
                <div class="acoes">
                    <button class="btn-opcao" data-toggle='modal' data-target='#modalAlterar'
                            data-id='<?= $tarefa->getId() ?>' data-nome='<?php echo $tarefa->getNome() ?>'
                            data-desc='<?= $tarefa->getDescricao() ?>'
                            data-dtinicio='<?php echo $tarefa->getDataInicio()->format('d/m/Y') ?>'
                            data-dtconclusao="<?= $tarefa->getDataConclusao()->format('d/m/Y') ?>"
                            data-estado="<?php echo $tarefa->getEstado() ?>">
                        <img class="img-icon" src='/View/img/Icons/editarIcone.png' alt=''>
                    </button>
                    <form action="/acaoTarefa" method="post">
                        <input type="hidden" name="acao" value="excluirTarefa">
                        <input type="hidden" name="id" value="<?php echo $tarefa->getId() ?>">
                        <input type="hidden" name="idProjeto" value="<?= $_GET['idProjeto'] ?>">
                        <button class="btn-opcao">
                            <img class="img-icon" src='/View/img/Icons/lixeiraicone.png' alt=''>
                        </button>
                    </form>
                </div>
                <div class="acoes">
                    <button class="btn-opcao">
                        <a href="/menu/viagem?idTarefa=<?= $tarefa->getId() ?>">
                            <img class="img-icon" src="/View/img/Icons/viagemIcone.png" alt="">
                        </a>
                    </button>
                    <button class="btn-opcao">
                        <a href="/menu/compra?idTarefa=<?= $tarefa->getId() ?>">
                            <img class="img-icon" src="/View/img/Icons/Compra.png" alt="">
                        </a>
                    </button>
                    <button class="btn-opcao">
                        <a href="/menu/atividadePlanejada?idTarefa=<?= $tarefa->getId() ?>">
                            <img class="img-icon" src="/View/img/Icons/Atividade.png" alt="">
                        </a>
                    </button>
                </div>
            </footer>
        </div>
    <?php endforeach; ?>
    </div>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="/View/img/Icons/adicionar.png" class="img-icon">
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Tarefas</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoTarefa" method="post">
                    <div class="form-group">
                        <label for="nomeTarefa" class="col-form-label">Nome</label>
                        <input class="form-control" id="nomeTarefa" name="nomeTarefa">
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-form-label">Descricao</label>
                        <input class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class=form-group">
                        <label for="estado" class="col-form-label-select">Estado</label>
                        <select class="custom-select" name="estado" id="estado">
                            <option value="Trabalhando" selected>Trabalhando</option>
                            <option value="Concluido">Concluido</option>
                            <option value="Travado">Travado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dtInicio" class="col-form-label">Data de Inicio</label>
                        <input type="text" class="form-control" id="dtInicio" name="dtInicio">
                    </div>
                    <div class="form-group">
                        <label for="dtConclusao" class="col-form-label">Data de Conclusão</label>
                        <input type="text" class="form-control" id="dtConclusao" name="dtConclusao">
                    </div>
                    <input type="hidden" name="acao" value="cadastrarTarefa">
                    <input type="hidden" name="idProjeto" value="<?= $_GET['idProjeto'] ?>">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Tarefas</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoTarefa" method="post">
                    <div class="form-group">
                        <label for="nomeTarefa" class="col-form-label">Nome</label>
                        <input class="form-control" id="nomeTarefa" name="nomeTarefa">
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-form-label">Descricao</label>
                        <input class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class=form-group">
                        <label for="estado" class="col-form-label-select">Estado</label>
                        <select class="custom-select" name="estado" id="estado">
                            <option value="Trabalhando" selected>Trabalhando</option>
                            <option value="Concluido">Concluido</option>
                            <option value="Travado">Travado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dtInicio" class="col-form-label">Data de Inicio</label>
                        <input type="text" class="form-control" id="dtInicio" name="dtInicio">
                    </div>
                    <div class="form-group">
                        <label for="dtConclusao" class="col-form-label">Data de Conclusão</label>
                        <input type="text" class="form-control" id="dtConclusao" name="dtConclusao">
                    </div>
                    <input type="hidden" name="acao" value="alterarTarefa">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="idProjeto" value="<?= $_GET['idProjeto'] ?>">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/View/js/jquery.js"></script>
<script src="/View/js/bootstrap.js"></script>
<script src="/View/js/funcoesTarefa.js"></script>
</body>
</html>