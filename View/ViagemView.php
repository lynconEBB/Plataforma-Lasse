<?php
require_once '../Services/Autoload.php';
LoginControl::verificar();

include "cabecalho.php";

$viagemControl = new ViagemControl();
$viagemControl->verificaPermissao();

$resul = $viagemControl->listarPorIdTarefa($_GET['idTarefa']);

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

<button onclick="location.href='ViagemCadastroView.php?idTarefa=<?= $_REQUEST['idTarefa']?>'" type="button" class="btn btn-primary">
    Cadastrar Nova Viagem
</button>


<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesViagem.js"></script>
</body>
</html>

