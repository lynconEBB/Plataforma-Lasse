<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../Default/css/styleVeiculo.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Veiculos</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../../../assets/images/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">
            <a href="/menu/condutor" class="side-bar-icon">
                <img src="../../../assets/images/Icons/condutor.png" class="img-icon" alt="Menu de Veiculos">
            </a>
        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../../../assets/images/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <div class="container-veiculos">
        <header class="row-header">
            <h3>Nome</h3>
            <h3>Tipo</h3>
            <h3>Retirada</h3>
            <h3>Devolução</h3>
            <h3>Condutor</h3>
        </header>
        <?php
        foreach ($veiculos as $veiculo):
            ?>
        <div class="container-row">
            <h6><?= $veiculo->getNome()?></h6>
            <h6><?= $veiculo->getTipo()?></h6>
            <h6><?= $veiculo->getDataRetirada()?> - <?= $veiculo->getHorarioRetirada()?></h6>
            <h6><?= $veiculo->getDataDevolucao()?> - <?= $veiculo->getHorarioDevolucao()?></h6>
            <h6><?= $veiculo->getCondutor()->getNome()?></h6>
            <div class="botoes">
                <button class='btn-opcao' data-toggle='modal' data-target='#modalAlterar' data-id='<?= $veiculo->getId()?>' data-nome='<?= $veiculo->getNome()?>' data-tipo='<?= $veiculo->getTipo()?>'
                        data-dtret='<?= $veiculo->getDataRetirada()?>' data-dtdev='<?= $veiculo->getDataDevolucao()?>' data-horaret='<?= $veiculo->getHorarioRetirada()?>'
                        data-horadev='<?= $veiculo->getHorarioDevolucao()?>' data-idcond='<?= $veiculo->getCondutor()->getId()?>'>
                    <img class="img-icon" src='../../../assets/images/Icons/editarIcone.png' alt=''>
                </button>
                <form action="/acaoVeiculo" method="post">
                    <input type="hidden" name="acao" value="excluirVeiculo">
                    <input type="hidden" name="id" value="<?= $veiculo->getId()?>">
                    <button class='btn-opcao'><img class="img-icon" src='../../../assets/images/Icons/lixeiraIcone.png' alt=""></button>
                </form>
            </div>

        </div>
        <?php
        endforeach;
        ?>
    </div>
</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="../../../assets/images/Icons/adicionar.png" class="img-icon" alt="Botao para cadastrar">
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

<script src="../Default/js/jquery.js"></script>
<script src="../Default/js/bootstrap.js"></script>
<script src="../Default/js/funcoesVeiculo.js"></script>
</body>
</html>
