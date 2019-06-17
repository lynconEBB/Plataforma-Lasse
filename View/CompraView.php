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
        <th>Proposito</th>
        <th>Total Gasto</th>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
    <?php
        foreach ($compras as $compra):
    ?>
            <tr>
                <td><?=$compra->getProposito()?></td>
                <td><?=$compra->getTotalGasto()?></td>
                <td>
                    <button class='btn' data-toggle='modal' data-target="#modalAlterar" data-id="<?=$compra->getId()?>" data-proposito="<?=$compra->getProposito()?>"
                            data-idtarefa="<?=$_GET['idTarefa']?>"  >
                        <img width='16' src='../img/edit-regular.svg' alt=''>
                    </button>
                </td>
                <td>
                    <form action="/acaoCompra" method="post">
                        <input type="hidden" name="acao" value="excluirCompra">
                        <input type="hidden" name="id" value="<?php echo $compra->getId()?>">
                        <input type="hidden" name="idTarefa" value="<?php echo $_GET['idTarefa']?>">
                        <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                    </form>
                </td>
                <td>
                    <a href="/menu/compra/item?idCompra=<?=$compra->getId()?>"><button type="button" class="btn"><img width='16' src='../img/box-solid.svg' alt=''></button></a>
                </td>
            </tr>
    <?php
        endforeach;
    ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Nova Compra
</button>

<a href="/menu/item"><button type="button" class="btn btn-warning">Menu de Itens</button></a>

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

<script src="../js/jquery.js"></script>
<script src="../js/funcoesCompra.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>
