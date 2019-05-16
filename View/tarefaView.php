<?php
    require_once '../Control/LoginControl.php';
    LoginControl::verificar();

    require_once "cabecalho.php";
    require_once '../Control/TarefaControl.php';
    $tarefaControl = new TarefaControl();
    $tarefaControl->procuraProjeto();

    if(isset($_SESSION['idProjeto'])){
        $resul = $tarefaControl->listarPorIdProjeto($_SESSION['idProjeto']);
?>
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
        </thead>
        <tbody>
        <?php
            foreach ($resul as $registro):
        ?>
            <tr>
                <td><?php echo $registro->getNome() ?></td>
                <td><?php echo $registro->getDescricao() ?></td>
                <td><?php echo $registro->getEstado() ?></td>
                <td><?php echo $registro->getDataInicio() ?></td>
                <td><?php echo $registro->getDataConclusao() ?></td>
                <td>
                    <button class='btn' data-toggle='modal' data-target='#modalAlterar'
                            data-id='<?php echo $registro->getId() ?>' data-nome='<?php echo $registro->getNome() ?>'
                            data-desc='<?php echo $registro->getDescricao() ?>' data-dtinicio='<?php echo $registro->getDataInicio() ?>'
                            data-dtconclusao="<?php echo $registro->getDataConclusao() ?>" data-estado="<?php echo $registro->getEstado() ?>" >
                        <img width='16' src='../img/edit-regular.svg' alt=''>
                    </button>
                </td>
                <td>
                    <form style="display: inline;" action="../Control/TarefaControl.php" method="post">
                        <input type="hidden" name="acao" value="2">
                        <input type="hidden" name="id" value="<?php echo $registro->getId()?>">
                        <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                    </form>
                </td>
                <td>
                    <form style="display: inline;" action="viagemView.php" method="post">
                        <input type="hidden" name="idTarefa" value="<?php echo $registro->getId() ?>">
                        <button class="btn"><img width="16" src="../img/plane-solid.svg" alt=""></button>
                    </form>
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
                    <form action="../Control/TarefaControl.php" method="post">
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
                        <input type="hidden" name="acao" value="1">
                        <input type="hidden" name="idProjeto" value="<?php echo $_SESSION['idProjeto'] ?>">
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
                    <form action="../Control/TarefaControl.php" method="post">
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
                        <input type="hidden" name="acao" value="3">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="idProjeto" value="<?php echo $_SESSION['idProjeto'] ?>">
                        <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    }
?>

<script src="../js/jquery.js"></script>
<script src="../js/funcoesTarefa.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>