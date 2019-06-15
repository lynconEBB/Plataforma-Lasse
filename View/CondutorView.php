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
        <th>Número CNH</th>
        <th>Validade CNH</th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <?php
            foreach ($condutores as $condutor):
        ?>
        <tr>
            <td><?php echo $condutor->getNome() ?></td>
            <td><?php echo $condutor->getCnh() ?></td>
            <td><?php echo $condutor->getValidadeCNH() ?></td>
            <td>
                <button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?php echo $condutor->getId()?>' data-nome='<?php echo $condutor->getNome()?>' data-cnh='<?php echo $condutor->getCnh()?>' data-val='<?php echo $condutor->getValidadeCNH()?>'>
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
            </td>
            <td>
                <form action="/acaoCondutor" method="post">
                    <input type="hidden" name="acao" value="excluirCondutor">
                    <input type="hidden" name="id" value="<?php echo $condutor->getId()?>">
                    <button class='btn'><img width='16' src='../img/trash-alt-solid.svg'></button>
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
                <form action="/acaoCondutor" method="post">
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
                    <input type="hidden" name="acao" value="cadastrarCondutor">
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
                <form action="/acaoCondutor" method="post">
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
                    <input type="hidden" name="acao" value="alterarCondutor">
                    <input type="hidden" id="id" name="id">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.js"></script>
<script src="../js/funcoesCondutor.js"></script>
<script src="../js/bootstrap.js"></script>
</body>
</html>
