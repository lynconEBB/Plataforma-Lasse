<html>
<head>
    <title>Lasse - PTI</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../css/estiloViagemCadastro.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<table class="table table-hover">
    <thead>
    <th>Tipo</th>
    <th>Valor</th>
    <th></th>
    <th></th>
    </thead>
    <tbody>
    <?php

    foreach ($gastos as $gasto) {
            ?>
            <tr>
                <td><?=$gasto->getTipo()?></td>
                <td><?=$gasto->getValor()?></td>
                <td>
                    <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$gasto->getId()?>' data-tipo="<?=$gasto->getTipo()?>" data-valor="<?=$gasto->getValor()?>">
                        <img width='16' src='../../img/edit-regular.svg' alt=''>
                    </button>
                </td>
                <td>
                    <form action="/acaoGasto" method="post">
                        <input type="hidden" name="acao" value="excluirGasto">
                        <input type="hidden" name="id" value="<?=$gasto->getId()?>">
                        <input type="hidden" name="idViagem" value="<?=$_GET['idViagem']?>">
                        <button class='btn'><img width='16' src='../../img/trash-alt-solid.svg'></button>
                    </form>
                </td>
            </tr>
      <?php
    }
    ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Novo Gasto
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Gastos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoGasto" method="post">
                    <div class="form-group">
                        <label for="tipoGasto" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipoGasto" name="tipoGasto">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <input type="hidden" name="acao" value="cadastrarGasto">
                    <input type="hidden" value="<?=$_GET['idViagem']?>" name="idViagem">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Gasto</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoGasto" method="post">
                    <div class="form-group">
                        <label for="tipoGasto" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipoGasto" name="tipoGasto">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <input type="hidden" name="acao" value="alterarGasto">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" name="idViagem" value="<?=$_GET['idViagem']?>">
                    <button type="submit" class="btn btn-primary align-self-center">Alterar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/funcoesGasto.js"></script>
</body>
</html>

