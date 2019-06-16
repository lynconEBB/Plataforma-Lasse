<html>
<head>
    <title>Lasse - PTI</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php Mensagem::exibir('danger');?>
<div class="card-deck">
    <?php

    foreach ($projetos as $projeto):
        ?>
        <div class="card border-info" style="width:400px">
            <div class="card-body text-info">
                <h4 class='card-title'><?php echo $projeto->getNome() ?></h4>
                <div class='card-text'>
                    <p><?php echo $projeto->getDescricao() ?></p>
                    <p><b>Data de Inicio:</b><?php echo $projeto->getDataInicio() ?></p>
                    <p><b>Data de Finalização:</b><?php echo $projeto->getDataFinalizacao() ?></p>
                </div>
            </div>
            <div class="card-footer">
                <button class='btn' data-toggle='modal' data-target='#modalAlterar'
                        data-id='<?php echo $projeto->getId() ?>' data-nome='<?php echo $projeto->getNome() ?>'
                        data-desc='<?php echo $projeto->getDescricao() ?>'
                        data-dtini='<?php echo $projeto->getDataInicio() ?>'
                        data-dtfim='<?php echo $projeto->getDataFinalizacao() ?>'>
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
                <form style="display: inline;" action="/acaoProjeto" method="post">
                    <input type="hidden" name="acao" value="excluirProjeto">
                    <input type="hidden" name="id" value="<?php echo $projeto->getId() ?>">
                    <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                </form>
                <a href="/menu/tarefa?idProjeto=<?= $projeto->getId() ?>">
                    <button class="btn"><img width="16" src="../img/plus-solid.svg" alt=""></button>
                </a>
                <?php
                    $projetoControl = new ProjetoControl();
                    if($projetoControl->verificaDono($projeto->getId())):
                ?>
                <button type="button" class="btn " data-toggle="modal" data-target="#modalAdicionaFunc" data-idprojeto="<?=$projeto->getId()?>">
                    <img src="../img/Icons/iconeADD.png" width="20">
                </button>
                <?php
                    endif;
                ?>
            </div>
        </div>
    <?php
    endforeach;
    ?>
</div>

<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCadastro">
    Novo Projeto
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
<script src="../js/funcoesProjeto.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>