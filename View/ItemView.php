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
            <th>Valor</th>
            <th>Quantidade</th>
        </thead>
        <tbody>
        <?php
            foreach ($itens as $item):
        ?>
            <tr>
                <td><?= $item->getNome() ?></td>
                <td><?= $item->getValor() ?></td>
                <td><?= $item->getQuantidade() ?></td>
        <?php

            endforeach;
        ?>
        </tbody>
    </table>

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
                    <form action="../Control/ItemControl.php" method="post">
                        <div class="form-group">
                            <label for="nome" class="col-form-label">Nome</label>
                            <input class="form-control" id="nome" name="nome">
                        </div>
                        <div class="form-group">
                            <label for="valor" class="col-form-label">Valor</label>
                            <input class="form-control" id="valor" name="valor">
                        </div>
                        <div class="form-group">
                            <label for="qtd" class="col-form-label">Quantidade</label>
                            <input type="text" class="form-control" id="qtd" name="qtd">
                        </div>
                        <input type="hidden" name="acao" value="cadastrarItem">
                        <input type="hidden" name="idCompra" value="<?= $_GET['idCompra'] ?>">
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
                    <form action="../Control/ItemControl.php" method="post">
                        <div class="form-group">
                            <label for="nome" class="col-form-label">Nome</label>
                            <input class="form-control" id="nome" name="nome">
                        </div>
                        <div class="form-group">
                            <label for="valor" class="col-form-label">Valor</label>
                            <input class="form-control" id="valor" name="valor">
                        </div>
                        <div class="form-group">
                            <label for="qtd" class="col-form-label">Quantidade</label>
                            <input type="text" class="form-control" id="qtd" name="qtd">
                        </div>
                        <input type="hidden" name="acao" value="alteraItem">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="idCompra" value="<?=$_GET['idCompra']?>">
                        <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="../js/jquery.js"></script>
<script src="../js/funcoesItem.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>