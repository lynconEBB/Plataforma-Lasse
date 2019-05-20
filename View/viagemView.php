<?php
require_once "../Control/LoginControl.php";
LoginControl::verificar();

include "cabecalho.php";
require_once '../Control/ViagemControl.php';
require_once '../Control/VeiculoControl.php';

$viagemControl = new ViagemControl();
$resul = $viagemControl->listarPorIdTarefa($_REQUEST['idTarefa']);

?>
<table class="table table-hover">
    <thead>
        <th>Nome Viajante</th>
        <th>Veiculo</th>
        <th>Condutor</th>
        <th>Origem</th>
        <th>Destino</th>
        <th>Justificativa</th>
        <th>Observacoes</th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <?php
        foreach ($resul as $obj) {
            echo '<tr>';
            echo "<td>{$obj->getViajante()->getNomeCompleto()}</td>";
            echo "<td>{$obj->getVeiculo()->getNome()}</td>";
            echo "<td>{$obj->getVeiculo()->getCondutor()->getNome()}</td>";
            echo "<td>{$obj->getOrigem()}</td>";
            echo "<td>{$obj->getDestino()}</td>";
            echo "<td>{$obj->getJustificativa()}</td>";
            echo "<td>{$obj->getObservacoes()}</td>";
            echo "<td>";
            echo "<button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='{$obj->getId()}'>";
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
    Cadastrar Nova Viagem
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Control/ViagemControl.php" method="post">
                    <div class="form-group">
                        <label for="origem" class="col-form-label">Origem</label>
                        <input class="form-control" id="origem" name="origem">
                    </div>
                    <div class="form-group">
                        <label for="destino" class="col-form-label">Destino</label>
                        <input class="form-control" id="destino" name="destino">
                    </div>
                    <div class="form-group">
                        <label for="dtIda" class="col-form-label">Data de Ida</label>
                        <input type="text" class="form-control" id="dtIda" name="dtIda">
                    </div>
                    <div class="form-group">
                        <label for="dtVolta" class="col-form-label">Data de Volta</label>
                        <input type="text" class="form-control" id="dtVolta" name="dtVolta">
                    </div>
                    <div class="form-group">
                        <label for="justificativa" class="col-form-label">Justificativa</label>
                        <input type="text" class="form-control" id="justificativa" name="justificativa">
                    </div>
                    <div class="form-group">
                        <label for="observacoes" class="col-form-label">Observações</label>
                        <input type="text" class="form-control" id="observacoes" name="observacoes">
                    </div>
                    <div class="form-group">
                        <label for="passagem" class="col-form-label">Passagem</label>
                        <input type="text" class="form-control" id="passagem" name="passagem">
                    </div>
                    <div class="form-group">
                        <label for="idVeiculo">Veiculo</label>
                        <select class="custom-select" name="idVeiculo" id="idVeiculo">
                            <option selected>Escolha um Veiculo</option>
                            <?php
                            $veiculoControl = new VeiculoControl();
                            $veiculos = $veiculoControl->listar();
                            foreach ($veiculos as $veiculo){
                                echo "<option value='{$veiculo->getId()}'>{$veiculo->getNome()}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="dtEntradaHosp" class="col-form-label">Data de Entrada da Hospedagem</label>
                            <input type="text" class="form-control" id="dtEntradaHosp" name="dtEntradaHosp">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="dtSaidaHosp" class="col-form-label">Data de Saida da Hospedagem</label>
                            <input type="text" class="form-control" id="dtSaidaHosp" name="dtSaidaHosp">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="horaEntradaHosp" class="col-form-label">Horário de Entrada da Hospedagem</label>
                            <input type="text" class="form-control" id="horaEntradaHosp" name="horaEntradaHosp">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="horaSaidaHosp" class="col-form-label">Horário de Saida da Hospedagem</label>
                            <input type="text" class="form-control" id="horaSaidaHosp" name="horaSaidaHosp">
                        </div>
                    </div>
                    <input type="hidden" name="acao" value="1">
                    <input type="hidden" name="idTarefa" value="<?php echo $_REQUEST['idTarefa']?>">
                    <input type="hidden" name="idFuncionario" value="<?php echo $_SESSION['usuario-id']?>">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesViagem.js"></script>
</body>
</html>

