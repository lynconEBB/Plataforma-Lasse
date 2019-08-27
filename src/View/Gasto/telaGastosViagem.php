<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8"/>
    <title>LPM - Menu Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/styleGasto.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header class="page-header">
    <span class="titulo-header">Gastos de Viagem</span>
</header>

<div class="side-bar-back">
    <aside class="side-bar">
        <article class="side-bar-start">
            <a href="javascript:history.go(-1)" title="Return to the previous page" class="side-bar-icon">
                <img src="../../img/Icons/voltar.png" class="img-icon" alt="Icone para voltar a pagina anterior">
            </a>
        </article>
        <article class="side-bar-middle">

        </article>
        <article class="side-bar-end">
            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button"><img src="../../img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
            </form>
        </article>
    </aside>
</div>

<main class="main-content">
    <div class="container-gastos viagem">
        <?php foreach ($gastos as $gasto): ?>
            <div class="gasto-viagem">
                <h5><?=$gasto->getTipo()?></h5>
                <h5><?=$gasto->getValor()?></h5>
                <button class='btn-opcao' data-toggle='modal' data-target='#modalAlterar' data-id='<?=$gasto->getId()?>' data-tipo="<?=$gasto->getTipo()?>" data-valor="<?=$gasto->getValor()?>">
                    <img class="img-icon" src='../../img/Icons/editarIcone.png' alt=''>
                </button>
                <form action="/acaoGasto" method="post">
                    <input type="hidden" name="acao" value="excluirGasto">
                    <input type="hidden" name="id" value="<?=$gasto->getId()?>">
                    <input type="hidden" name="idViagem" value="<?=$_GET['idViagem']?>">
                    <button class='btn-opcao'><img class="img-icon" src='../../img/Icons/lixeiraIcone.png'></button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>

</main>

<button type="button" class="add-button" data-toggle="modal" data-target="#modalCadastro">
    <img src="../../img/Icons/adicionar.png" class="img-icon" alt="Botao para cadastrar">
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Gastos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoGasto" method="post">
                    <div class="form-group">
                        <label for="tipoGasto" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipoGasto" name="tipoGasto">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <input type="hidden" name="acao" value="cadastrarGasto">
                    <input type="hidden" value="<?=$_GET['idViagem']?>" name="idViagem">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Gasto</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/acaoGasto" method="post">
                    <div class="form-group">
                        <label for="tipoGasto" class="col-form-label">Tipo</label>
                        <input class="form-control" id="tipoGasto" name="tipoGasto">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-form-label">Valor</label>
                        <input class="form-control" id="valor" name="valor">
                    </div>
                    <input type="hidden" name="acao" value="alterarGasto">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" name="idViagem" value="<?=$_GET['idViagem']?>">
                    <button type="submit" class="btn btn-primary align-self-center">Alterar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/funcoesGasto.js"></script>
</body>
</html>