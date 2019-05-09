<?php
include "cabecalho.php";
require_once '../Control/VeiculoControl.php';
require_once '../Control/CondutorControl.php';

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
    foreach ($resul as $obj){
        echo '<tr>';
        echo "<td>{$obj->getNome()}</td>";
        echo "<td>{$obj->getTipo()}</td>";
        echo "<td>{$obj->getDataRetirada()}</td>";
        echo "<td>{$obj->getDataDevolucao()}</td>";
        echo "<td>{$obj->getHorarioRetirada()}</td>";
        echo "<td>{$obj->getHorarioDevolucao()}</td>";
        echo "<td>{$obj->getCondutor()->getNome()}</td>";
        echo "<td>";
        echo "<button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='{$obj->getId()}' data-nome='{$obj->getNome()}' data-tipo='{$obj->getTipo()}' data-dtret='{$obj->getDataRetirada()}' data-dtdev='{$obj->getDataDevolucao()}' data-horaret='{$obj->getHorarioRetirada()}' data-horadev='{$obj->getHorarioDevolucao()}' data-idcond='{$obj->getCondutor()->getId()}'>";
        echo "<img width='16' src='../img/edit-regular.svg' alt=''>";
        echo "</button>";
        echo "</td>";
        echo "<td><button class='btn'><a href='../Control/VeiculoControl.php?acao=2&id={$obj->getId()}'><img width='16' src='../img/trash-alt-solid.svg'></a></button></td>";
        echo '</tr>';
    }
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