<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/View/css/styleAtividade.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<header class="page-header">
    <span class="titulo-header">Atividades Não Planejadas</span>
    <?php \Lasse\LPM\Services\Mensagem::exibir('danger');?>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="/menu/usuario" class="side-bar-icon">
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
    <div class="container-atividades">
        <?php
        if($atividades != false):
            foreach ($atividades as $atividade):
        ?>
                <div class="atividadeN-row">
                    <div class="cell">
                        <h4>Tipo</h4>
                        <h5><?= $atividade->getTipo() ?></h5>
                    </div>
                    <div class="cell">
                        <h4>Comentário</h4>
                        <h5><?= $atividade->getComentario() ?></h5>
                    </div>
                    <div class="cell">
                        <h4>Tempo Gasto</h4>
                        <h5><?= $atividade->getTempoGasto() ?></h5>
                    </div>
                    <div class="cell">
                        <h4>Total Gasto</h4>
                        <h5><?= $atividade->getTotalGasto() ?></h5>
                    </div>
                    <button class='btn-opcao' data-toggle='modal' data-target="#modalAlterar"
                            data-id="<?= $atividade->getId() ?>"
                            data-tempogasto="<?= $atividade->getTempoGasto() ?>"
                            data-tipo="<?= $atividade->getTipo() ?>"
                            data-comentario="<?= $atividade->getComentario() ?>"
                            data-datarealizacao="<?= $atividade->getDataRealizacao()->format('d/m/Y') ?>">
                        <img class="img-icon" src='/View/img/Icons/editarIcone.png' alt=''>
                    </button>
                    <form action="/acaoAtividade" method="post">
                        <input type="hidden" name="acao" value="excluirAtividade">
                        <input type="hidden" name="id" value="<?= $atividade->getId() ?>">
                        <button class="btn-opcao">
                            <img class="img-icon" src='/View/img/Icons/lixeiraicone.png' alt=''>
                        </button>
                    </form>
                </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="/View/img/Icons/adicionar.png" class="img-icon" alt="Icone para cadastrar uma nova Atividade">
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Atividade</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoAtividade" method="post">
                    <div class="form-group">
                        <label for="tipo">Tipo de Atividade</label>
                        <select class="custom-select" name="tipo" id="tipo">
                            <option value="Atraso">Atraso</option>
                            <option value="Consulta">Consulta</option>
                            <option value="Doença">Doença</option>
                            <option value="Viagem">Viagem</option>
                            <option value="Acidente">Acidente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comentario" class="col-form-label">Comentario</label>
                        <input class="form-control" id="comentario" name="comentario">
                    </div>
                    <div class="form-group">
                        <label for="tempoGasto" class="col-form-label">Tempo Gasto(h)</label>
                        <input class="form-control" id="tempoGasto" name="tempoGasto">
                    </div>
                    <div class="form-group">
                        <label for="dataRealizacao" class="col-form-label">Data de Realizacao</label>
                        <input class="form-control" id="dataRealizacao" name="dataRealizacao">
                    </div>

                    <input type="hidden" name="acao" value="cadastrarAtividade">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Atividade</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoAtividade" method="post">
                    <div class="form-group">
                        <label for="tipo">Tipo de Atividade</label>
                        <select class="custom-select" name="tipo" id="tipo">
                            <option value="Atraso">Atraso</option>
                            <option value="Consulta">Consulta</option>
                            <option value="Doença">Doença</option>
                            <option value="Viagem">Viagem</option>
                            <option value="Acidente">Acidente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comentario" class="col-form-label">Comentario</label>
                        <input class="form-control" id="comentario" name="comentario">
                    </div>
                    <div class="form-group">
                        <label for="tempoGasto" class="col-form-label">Tempo Gasto</label>
                        <input class="form-control" id="tempoGasto" name="tempoGasto">
                    </div>
                    <div class="form-group">
                        <label for="dataRealizacao" class="col-form-label">Data de Realizacao</label>
                        <input class="form-control" id="dataRealizacao" name="dataRealizacao">
                    </div>

                    <input type="hidden" name="acao" value="alterarAtividade">
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-primary align-self-center">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/View/js/jquery.js"></script>
<script src="/View/js/bootstrap.js"></script>
<script src="/View/js/funcoesAtividade.js"></script>
</body>
</html>