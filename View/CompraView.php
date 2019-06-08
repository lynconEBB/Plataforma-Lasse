<?php
require_once '../Services/Autoload.php';
LoginControl::verificar();

include "cabecalho.php";

$compraControl = new CompraControl();
$resul = $compraControl->listar();
?>
<table class="table table-hover">
    <thead>
    <th>Nome</th>
    <th>Número CNH</th>
    <th>Validade CNH</th>
    <th></th>
    <th></th>
    </thead>
    <tbody>
    <?php
    foreach ($resul as $registro){
        echo '<tr>';
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>";
        echo "<button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='{$registro->getId()}' data-nome='{$registro->getNome()}' data-cnh='{$registro->getCnh()}' data-val='{$registro->getValidadeCNH()}'>";
        echo "<img width='16' src='../img/edit-regular.svg' alt=''>";
        echo "</button>";
        echo "</td>";
        echo "<td><button class='btn'><a href='../Control/CondutorControl.php?acao=2&id=".$registro->getId()."'><img width='16' src='../img/trash-alt-solid.svg'></a></button></td>";
        echo '</tr>';
    }
    ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Nova Compra
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
                <form action="../Control/CompraControl.php" method="post">
                    <div class="form-group">
                        <label for="proposito" class="col-form-label">Proposito da Compra</label>
                        <input class="form-control" id="proposito" name="proposito">
                    </div>
                    <div class="row" id="compras">
                    </div>

                    <button style="display: block;" class="btn" type="button" id="adicionar-item">&plus;</button>

                    <input type="hidden" name="acao" value="1">
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
                <form action="../Control/CondutorControl.php" method="post">
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
                    <input type="hidden" name="acao" value="3">
                    <input type="hidden" id="id" name="id">
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
