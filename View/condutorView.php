<?php
    include 'cabecalho.php';
    require_once '../Control/CondutorControl.php';

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
        foreach ($resul as $registro){
            echo '<tr>';
                echo "<td>{$registro->getNome()}</td>";
                echo "<td>{$registro->getCnh()}</td>";
                echo "<td>{$registro->getValidadeCNH()}</td>";
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
    include 'rodape.php';
?>
