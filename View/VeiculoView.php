<html>
<head>
    <title>Lasse - PTI</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/styleVeiculo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

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
        foreach ($veiculos as $veiculo):
    ?>
        <tr>
            <td><?= $veiculo->getNome()?></td>
            <td><?= $veiculo->getTipo()?></td>
            <td><?= $veiculo->getDataRetirada()?></td>
            <td><?= $veiculo->getDataDevolucao()?></td>
            <td><?= $veiculo->getHorarioRetirada()?></td>
            <td><?= $veiculo->getHorarioDevolucao()?></td>
            <td><?= $veiculo->getCondutor()->getNome()?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?= $veiculo->getId()?>' data-nome='<?= $veiculo->getNome()?>' data-tipo='<?= $veiculo->getTipo()?>'
                        data-dtret='<?= $veiculo->getDataRetirada()?>' data-dtdev='<?= $veiculo->getDataDevolucao()?>' data-horaret='<?= $veiculo->getHorarioRetirada()?>'
                        data-horadev='<?= $veiculo->getHorarioDevolucao()?>' data-idcond='<?= $veiculo->getCondutor()->getId()?>'>
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="/acaoVeiculo" method="post">
                    <input type="hidden" name="acao" value="excluirVeiculo">
                    <input type="hidden" name="id" value="<?= $veiculo->getId()?>">
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

<a href="/menu/condutor"><button type="button" class="btn btn-warning">Menu de Condutores</button></a>

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
                <form action="/acaoVeiculo" method="post">
                    <div class="form-group">
                        <label for="nome" class="col-form-label">Nome</label>
                        <input class="form-control" id="nome" name="nomeVeiculo">
                    </div>
                    <div class="form-group">
                        <label for="tipo" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipo" name="tipoVeiculo">
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
                    <div class="form-group campo-idCondutor">
                        <label for="idCondutor">Condutor</label>
                        <select class="custom-select" name="idCondutor" id="idCondutorCadastro">
                            <option value="escolher" selected>Escolha um Condutor</option>
                            <option style="display: none;" value="novo" ></option>
                        <?php
                            foreach ($condutores as $condutor){
                                echo "<option value='{$condutor->getId()}'>{$condutor->getNome()}</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <button type="button" class="btn" id="novo-condutor">&plus;</button>
                    <div id="form-condutor">
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
                    </div>
                    <input type="hidden" name="acao" value="cadastrarVeiculo">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Veiculos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoVeiculo" method="post">
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
                    <div class="form-group campo-idCondutor-alter">
                        <label for="idCondutor">Condutor</label>
                        <select class="custom-select" name="idCondutor" id="idCondutorAlteracao">

                            <?php
                            foreach ($condutores as $condutor){
                                echo "<option value='{$condutor->getId()}'>{$condutor->getNome()}</option>";
                            }
                            ?>
                            <option style="display: none;" value="novo" ></option>
                        </select>
                    </div>
                    <button type="button" class="btn" id="novo-condutor-alter">&plus;</button>
                    <div id="form-condutor-alter">
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
                    </div>
                    <input type="hidden" id="last-id-condutor">
                    <input type="hidden" name="acao" value="alterarVeiculo">
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-primary align-self-center">Alterar</button>
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