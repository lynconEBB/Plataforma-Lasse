<?php
include "cabecalho.php";
require_once '../Services/Autoload.php';

$veiculoControl = new VeiculoControl();
$resul = $veiculoControl->listar();

?>
<table class="table table-hover">
    <thead>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Data Retirada</th>
        <th>Data Devolução</th>
        <th>Horario Retirada</th>
        <th>Horario Devolução</th>
        <th>Nome Condutor</th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
    <?php
        foreach ($resul as $obj):
    ?>
        <tr>
            <td><?php echo $obj->getNome()?></td>
            <td><?php echo $obj->getTipo()?></td>
            <td><?php echo $obj->getDataRetirada()?></td>
            <td><?php echo $obj->getDataDevolucao()?></td>
            <td><?php echo $obj->getHorarioRetirada()?></td>
            <td><?php echo $obj->getHorarioDevolucao()?></td>
            <td><?php echo $obj->getCondutor()->getNome()?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?php echo $obj->getId()?>' data-nome='<?php echo $obj->getNome()?>' data-tipo='<?php echo $obj->getTipo()?>'
                        data-dtret='<?php echo $obj->getDataRetirada()?>' data-dtdev='<?php echo $obj->getDataDevolucao()?>' data-horaret='<?php echo $obj->getHorarioRetirada()?>'
                        data-horadev='<?php echo $obj->getHorarioDevolucao()?>' data-idcond='<?php echo $obj->getCondutor()->getId()?>'>
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="../Control/VeiculoControl.php" method="post">
                    <input type="hidden" name="acao" value="2">
                    <input type="hidden" name="id" value="<?php echo $obj->getId()?>">
                    <button class='btn'><img width='16' src='../img/trash-alt-solid.svg' alt=""></button>
                </form>
            </td>
        </tr>
    <?php
     endforeach;
    ?>
    </tbody>
</table>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
    Cadastrar Novo Veículo
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Control/VeiculoControl.php" method="post">
                    <div class="form-group">
                        <label for="nome" class="col-form-label">Nome</label>
                        <input class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="tipo" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipo" name="tipo">
                    </div>
                    <div class="form-group">
                        <label for="dtRetirada" class="col-form-label">Data de Retirada</label>
                        <input type="text" class="form-control" id="dtRetirada" name="dtRetirada">
                    </div>
                    <div class="form-group">
                        <label for="dtDevolucao" class="col-form-label">Data de Devolução</label>
                        <input type="text" class="form-control" id="dtDevolucao" name="dtDevolucao">
                    </div>
                    <div class="form-group">
                        <label for="horarioRetirada" class="col-form-label">Horario de Retirada</label>
                        <input type="text" class="form-control" id="horarioRetirada" name="horarioRetirada">
                    </div>
                    <div class="form-group">
                        <label for="horarioDevolucao" class="col-form-label">Horário de Devolução</label>
                        <input type="text" class="form-control" id="horarioDevolucao" name="horarioDevolucao">
                    </div>
                    <div class="form-group">
                        <label for="idCondutor">Condutor</label>
                        <select class="custom-select" name="idCondutor" id="idCondutor">
                            <option selected>Escolha um Condutor</option>
                        <?php
                            $condControl = new CondutorControl();
                            $condutores = $condControl->listar();
                            foreach ($condutores as $condutor){
                                echo "<option value='{$condutor->getId()}'>{$condutor->getNome()}</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="acao" value="1">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlterar" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Control/VeiculoControl.php" method="post">
                    <div class="form-group">
                        <label for="nome" class="col-form-label">Nome</label>
                        <input class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="tipo" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipo" name="tipo">
                    </div>
                    <div class="form-group">
                        <label for="dtRetirada" class="col-form-label">Data de Retirada</label>
                        <input type="text" class="form-control" id="dtRetirada" name="dtRetirada">
                    </div>
                    <div class="form-group">
                        <label for="dtDevolucao" class="col-form-label">Data de Devolução</label>
                        <input type="text" class="form-control" id="dtDevolucao" name="dtDevolucao">
                    </div>
                    <div class="form-group">
                        <label for="horarioRetirada" class="col-form-label">Horario de Retirada</label>
                        <input type="text" class="form-control" id="horarioRetirada" name="horarioRetirada">
                    </div>
                    <div class="form-group">
                        <label for="horarioDevolucao" class="col-form-label">Horário de Devolução</label>
                        <input type="text" class="form-control" id="horarioDevolucao" name="horarioDevolucao">
                    </div>
                    <div class="form-group">
                        <label for="idCondutor">Condutor</label>
                        <select class="custom-select" name="idCondutor" id="idCondutor">
                            <?php
                            foreach ($condutores as $condutor){
                                echo "<option value='{$condutor->getId()}'>{$condutor->getNome()}</option>";
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


<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesVeiculo.js"></script>
</body>
</html>