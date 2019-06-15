<?php

include "cabecalho.php";

$compraControl = new CompraControl();
$compraControl->verificaPermissao();
$resul = $compraControl->listarPorIdTarefa($_GET['idTarefa']);

?>
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
        foreach ($resul as $registro):
    ?>
            <tr>
                <td><?=$registro->getProposito()?></td>
                <td><?=$registro->getTotalGasto()?></td>
                <td>
                    <button class='btn' data-toggle='modal' data-target="#modalAlterar" data-id="<?=$registro->getId()?>" data-proposito="<?=$registro->getProposito()?>"
                            data-idtarefa="<?=$_GET['idTarefa']?>"  >
                        <img width='16' src='../server/img/edit-regular.svg' alt=''>
                    </button>
                </td>
                <td>
                    <form action="../Control/CompraControl.php" method="post">
                        <input type="hidden" name="acao" value="2">
                        <input type="hidden" name="id" value="<?php echo $registro->getId()?>">
                        <button class="btn"><img width='16' src='../server/img/trash-alt-solid.svg' alt=''></button>
                    </form>
                </td>
                <td>
                    <a href="ItemView.php?idCompra=<?=$registro->getId()?>"><button type="button" class="btn"><img width='16' src='../server/img/box-solid.svg' alt=''></button></a>
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

<a href="ItemView.php"><button type="button" class="btn btn-warning">Menu de Itens</button></a>

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
                <form action="../Control/CompraControl.php" method="post">
                    <div class="form-group">
                        <label for="proposito" class="col-form-label">Proposito da Compra</label>
                        <input class="form-control" id="proposito" name="proposito">
                    </div>
                    <div class="form-group" id="itensCadastro">
                        <div id="container-itens"></div>
                    </div>

                    <button style="display: block;" class="btn" type="button" id="adicionar-item">&plus;</button>
                    <input type="hidden" name="idTarefa" value="<?=$_GET['idTarefa'];?>">
                    <input type="hidden" name="acao" value="1">
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
                <form action="../Control/CompraControl.php" method="post">
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
                                $tarefaControl = new TarefaControl();
                                $tarefas = $tarefaControl->listarPorIdUsaurio($_SESSION['usuario-id']);
                                foreach ($tarefas as $tarefa){
                                    echo "<option value='{$tarefa->getId()}'>{$tarefa->getNome()}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="acao" value="3">
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../server/js/jquery.js"></script>
<script src="../server/js/funcoesCompra.js"></script>
<script src="../server/js/bootstrap.js"></script>
</body>
</html>
