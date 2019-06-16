<html>
<head>
    <title>Lasse - PTI</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<table class="table table-hover">
    <thead>
    <th>Nome</th>
    <th>Descricao</th>
    <th>Estado</th>
    <th>Data de inicio</th>
    <th>Data de Conclusão</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    </thead>
    <tbody>
    <?php
    foreach ($tarefas as $tarefa):
        ?>
        <tr>
            <td><?= $tarefa->getNome() ?></td>
            <td><?= $tarefa->getDescricao() ?></td>
            <td><?= $tarefa->getEstado() ?></td>
            <td><?= $tarefa->getDataInicio() ?></td>
            <td><?= $tarefa->getDataConclusao() ?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar'
                        data-id='<?= $tarefa->getId() ?>' data-nome='<?php echo $tarefa->getNome() ?>'
                        data-desc='<?= $tarefa->getDescricao() ?>'
                        data-dtinicio='<?php echo $tarefa->getDataInicio() ?>'
                        data-dtconclusao="<?= $tarefa->getDataConclusao() ?>"
                        data-estado="<?php echo $tarefa->getEstado() ?>">
                    <img width='35' src='../img/Icons/editarIcone.png' alt=''>
                </button>
            </td>
            <td>
                <form style="display: inline;" action="/acaoTarefa" method="post">
                    <input type="hidden" name="acao" value="excluirTarefa">
                    <input type="hidden" name="id" value="<?php echo $tarefa->getId() ?>">
                    <input type="hidden" name="idProjeto" value="<?= $_GET['idProjeto'] ?>">
                    <button class="btn"><img width='25' src='../img/Icons/lixeiraicone.png' alt=''></button>
                </form>
            </td>
            <td>
                <a href="/menu/viagem?idTarefa=<?= $tarefa->getId() ?>">
                    <button class="btn"><img width="30" src="../img/Icons/viagemIcone.png" alt=""></button>
                </a>
            </td>
            <td>
                <a href="/menu/compra?idTarefa=<?= $tarefa->getId() ?>">
                    <button class="btn"><img width="20" src="../img/Icons/Compra.png" alt=""></button>
                </a>
            </td>
            <td>
                <a href="/menu/atividadePlanejada?idTarefa=<?= $tarefa->getId() ?>">
                    <button class="btn"><img width="25" src="../img/Icons/Atividade.png" alt=""></button>
                </a>
            </td>
        </tr>
    <?php
    endforeach;
    ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Nova Tarefa
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Itens</h5>
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

<script src="../js/jquery.js"></script>
<script src="../js/funcoesTarefa.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>