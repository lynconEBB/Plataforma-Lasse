<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleProjeto.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

    <header class="page-header">
        <?php Mensagem::exibir('danger');?>

        <div style="display: block; width: 100%">
            <span class="titulo-header">Projetos</span>
        </div>
    </header>

    <div class="side-bar-back">
        <aside class="side-bar">
            <article class="side-bar-start">
                <a href="/menu/usuario" class="side-bar-icon">
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
        <?php

        foreach ($projetos as $projeto):
            ?>
            <article class="container-projeto">
                <main class="info-projeto">
                    <h2 class="title-projeto"><?php echo $projeto->getNome() ?></h2>
                    <span class="detail-projeto">Data de Inicio: <?php echo $projeto->getDataInicio() ?></span>
                    <span class="detail-projeto">Data de Finalização: <?php echo $projeto->getDataFinalizacao()?></span>
                    <span class="detail-projeto">Total Gasto: <?=$projeto->getTotalGasto() ?></span>
                </main>
                <footer class="footer-projeto">
                        <a href="/menu/tarefa?idProjeto=<?= $projeto->getId() ?>" >
                            <button class="opcao-projeto">
                                <img width="16" src="../img/Icons/tarefa.png" alt="" class="img-icon">
                            </button>
                        </a>

                    <?php
                    $projetoControl = new ProjetoControl();
                    if($projetoControl->verificaDono($projeto->getId())):
                        ?>
                        <button type="button" class="opcao-projeto" data-toggle="modal" data-target="#modalAdicionaFunc" data-idprojeto="<?=$projeto->getId()?>">
                            <img src="../img/Icons/IconeADD.png" class="img-icon">
                        </button>
                        <button class='opcao-projeto' data-toggle='modal' data-target='#modalAlterar'
                                data-id='<?php echo $projeto->getId() ?>' data-nome='<?php echo $projeto->getNome() ?>'
                                data-desc='<?php echo $projeto->getDescricao() ?>'
                                data-dtini='<?php echo $projeto->getDataInicio() ?>'
                                data-dtfim='<?php echo $projeto->getDataFinalizacao() ?>'>
                            <img width='16' src='../img/Icons/editarIcone.png' alt='' class="img-icon">
                        </button>
                        <form style="display: inline;" action="/acaoProjeto" method="post">
                            <input type="hidden" name="acao" value="excluirProjeto">
                            <input type="hidden" name="id" value="<?php echo $projeto->getId() ?>">
                            <button class="opcao-projeto">
                                <img width='16' src='../img/Icons/lixeiraicone.png' alt='' class="img-icon">
                            </button>
                        </form>
                    <?php
                    endif;
                    ?>
                </footer>
            </article>
        <?php
        endforeach;
        ?>
    </main>

    <button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
        <img src="../img/Icons/adicionar.png" class="img-icon">
    </button>

    <div class="modal fade" id="modalCadastro" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <header class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Projetos</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </header>
                <div class="modal-body">
                    <form action="/acaoProjeto" method="post">
                        <div class="form-group">
                            <label for="nomeProjeto" class="col-form-label">Nome do Projeto</label>
                            <input class="form-control" id="nomeProjeto" name="nomeProjeto">
                        </div>
                        <div class="form-group">
                            <label for="descricao" class="col-form-label">Descrição</label>
                            <input class="form-control" id="descricao" name="descricao">
                        </div>
                        <div class="form-group">
                            <label for="dataInicio" class="col-form-label">Data de Início</label>
                            <input type="text" class="form-control" id="dataInicio" name="dataInicio">
                        </div>
                        <div class="form-group">
                            <label for="dataFinalizacao" class="col-form-label">Data de Finalização</label>
                            <input type="text" class="form-control" id="dataFinalizacao" name="dataFinalizacao">
                        </div>
                        <input type="hidden" name="acao" value="cadastrarProjeto">
                        <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAlterar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <header class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Projetos</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </header>
                <div class="modal-body">
                    <form action="/acaoProjeto" method="post">
                        <div class="form-group">
                            <label for="nomeProjeto" class="col-form-label">Nome do Projeto</label>
                            <input class="form-control" id="nomeProjeto" name="nomeProjeto">
                        </div>
                        <div class="form-group">
                            <label for="descricao" class="col-form-label">Descrição</label>
                            <input class="form-control" id="descricao" name="descricao">
                        </div>
                        <div class="form-group">
                            <label for="dataInicio" class="col-form-label">Data de Início</label>
                            <input type="text" class="form-control" id="dataInicio" name="dataInicio">
                        </div>
                        <div class="form-group">
                            <label for="dataFinalizacao" class="col-form-label">Data de Finalização</label>
                            <input type="text" class="form-control" id="dataFinalizacao" name="dataFinalizacao">
                        </div>
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="acao" value="alterarProjeto">
                        <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAdicionaFunc" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <header class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Adicionar Funcionario</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </header>
                <div class="modal-body">
                    <form action="/acaoProjeto" method="post">
                        <div class="form-group">
                            <label for="idUsuario">Funcionario</label>
                            <select class="custom-select" name="idUsuario" id="idUsuario">
                                <option value="escolher" selected>Escolha um Funcionario</option>
                                <?php
                                foreach ($usuarios as $usuario){
                                    echo "<option value='{$usuario->getId()}'>{$usuario->getNomeCompleto()}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" value="adicionarFuncionario" name="acao">
                        <input type="hidden" id="idProjeto" name="idProjeto">
                        <button type="submit" class="btn btn-primary align-self-center">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesProjeto.js"></script>
</body>
</html>