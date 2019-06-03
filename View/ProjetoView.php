<?php
    require_once '../Services/Autoload.php';

    LoginControl::verificar();

    include 'cabecalho.php';
?>
<div class="card-deck">
    <?php
        $projControl =  new ProjetoControl();
        $resul = $projControl->listarPorIdUsuario($_SESSION['usuario-id']);
        foreach ($resul as $registro):
    ?>
        <div class="card border-info" style="width:400px">
            <div class="card-body text-info">
                <h4 class='card-title'><?php echo $registro->getNome()?></h4>
                <div class='card-text'>
                    <p><?php echo $registro->getDescricao()?></p>
                   <p><b>Data de Inicio:</b><?php echo $registro->getDataInicio()?></p>
                    <p><b>Data de Finalização:</b><?php echo $registro->getDataFinalizacao()?></p>
                </div>
            </div>
            <div class="card-footer">
                <button  class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='<?php echo $registro->getId()?>' data-nome='<?php echo $registro->getNome()?>'
                        data-desc='<?php echo $registro->getDescricao()?>' data-dtini='<?php echo $registro->getDataInicio()?>' data-dtfim='<?php echo $registro->getDataFinalizacao()?>' >
                    <img width='16' src='../img/edit-regular.svg' alt=''>
                </button>
                <form style="display: inline;" action="../Control/ProjetoControl.php" method="post">
                    <input type="hidden" name="acao" value="2">
                    <input type="hidden" name="id" value="<?php echo $registro->getId()?>">
                    <button class="btn"><img width='16' src='../img/trash-alt-solid.svg' alt=''></button>
                </form>
                <a href="TarefaView.php?idProjeto=<?= $registro->getId()?>"><button class="btn"><img width="16" src="../img/plus-solid.svg" alt=""></button></a>
           </div>
        </div>
    <?php
        endforeach;
    ?>
</div>

<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCadastro">
    Novo Projeto
</button>

<div class="modal fade" id="modalCadastro" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <header class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Projetos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </header>
            <div class="modal-body">
                <form action="../Control/ProjetoControl.php" method="post">
                    <div class="form-group">
                        <label for="nomeProjeto" class="col-form-label">Nome do Projeto</label>
                        <input class="form-control" id="nomeProjeto" name="nomeProjeto">
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-form-label">Descrição</label>
                        <input class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class="form-group">
                        <label for="dataInicio" class="col-form-label">Data de Início</label>
                        <input type="text" class="form-control" id="dataInicio" name="dataInicio">
                    </div>
                    <div class="form-group">
                        <label for="dataFinalizacao" class="col-form-label">Data de Finalização</label>
                        <input type="text" class="form-control" id="dataFinalizacao" name="dataFinalizacao">
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
            <header class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Projetos</h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </header>
            <div class="modal-body">
                <form action="../Control/ProjetoControl.php" method="post">
                    <div class="form-group">
                        <label for="nomeProjeto" class="col-form-label">Nome do Projeto</label>
                        <input class="form-control" id="nomeProjeto" name="nomeProjeto">
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-form-label">Descrição</label>
                        <input class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class="form-group">
                        <label for="dataInicio" class="col-form-label">Data de Início</label>
                        <input type="text" class="form-control" id="dataInicio" name="dataInicio">
                    </div>
                    <div class="form-group">
                        <label for="dataFinalizacao" class="col-form-label">Data de Finalização</label>
                        <input type="text" class="form-control" id="dataFinalizacao" name="dataFinalizacao">
                    </div>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="acao" value="3">
                    <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="../js/jquery.js"></script>
    <script src="../js/funcoesProjeto.js"></script>
    <script src="../js/bootstrap.js"></script>
</body>
</html>