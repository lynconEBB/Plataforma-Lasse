<?php
    include 'cabecalho.php';
    require_once '../Services/Autoload.php';

    $condControl = new CondutorControl();
    $resul = $condControl->listar();
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
            foreach ($resul as $registro):
        ?>
        <tr>
            <td><?php echo $registro->getNome() ?></td>
            <td><?php echo $registro->getCnh() ?></td>
            <td><?php echo $registro->getValidadeCNH() ?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?php echo $registro->getId()?>' data-nome='<?php echo $registro->getNome()?>' data-cnh='<?php echo $registro->getCnh()?>' data-val='<?php echo $registro->getValidadeCNH()?>'>
                    <img width='16' src='../server/img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="../Control/CondutorControl.php" method="post">
                    <input type="hidden" name="acao" value="2">
                    <input type="hidden" name="id" value="<?php echo $registro->getId()?>">
                    <button class='btn'><img width='16' src='../server/img/trash-alt-solid.svg'></button>
                </form>
            </td>
        </tr>
        <?php
            endforeach;
        ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Novo Condutor
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Condutores</h5>
                <button class="close" data-dismiss="modal">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Condutores</h5>
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
<?php
    include 'Rodape.php';
?>
