<?php
    require_once '../Services/Autoload.php';
    LoginControl::verificar();

    require_once "cabecalho.php";

    $itemControl = new ItemControl();
    if(isset($_GET['idCompra'])){
        $itens = $itemControl->listarPorIdCompra($_GET['idCompra']);
    }else{
        $itens = $itemControl->listar();
    }
?>
    <table class="table table-hover">
        <thead>
            <th>Nome</th>
            <th>Valor</th>
            <th>Quantidade</th>
            <?php
                if(isset($_GET['idCompra'])) {
            ?>
                <th></th>
                <th></th>
            <?php
                }
            ?>
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
                    if(isset($_GET['idCompra'])) {
                ?>
                <td>
                    <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?= $item->getId() ?>' data-nome='<?= $item->getNome() ?>'
                            data-valor='<?= $item->getValor() ?>' data-qtd="<?=$item->getQuantidade()?>">
                        <img width="50px" src='../img/Icons/editarIcone.png' alt=''>
                    </button>
                </td>
                <td>
                    <form action="../Control/ItemControl.php" method="post">
                        <input type="hidden" name="acao" value="excluiItem">
                        <input type="hidden" name="id" value="<?=$item->getId() ?>">
                        <input type="hidden" name="idCompra" value="<?= $_GET['idCompra'] ?>">
                        <button class="btn"><img width='40px' src='../img/Icons/lixeiraicone.png' alt=''></button>
                    </form>
                </td>
            </tr>
        <?php
            }
            endforeach;
        ?>
        </tbody>
    </table>
<?php
    if(isset($_GET['idCompra'])) {
?>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
        Cadastrar Novo Item
    </button>
<?php
    }
?>


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