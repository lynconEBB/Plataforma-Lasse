<?php
    include 'cabecalho.php';
    require_once '../Control/ProjetoControl.php';
    $projControl =  new ProjetoControl();
    $resul = $projControl->listar();
?>
<div class="card-deck">
    <?php
        foreach ($resul as $registro){
            echo '<div class="card border-info" style="width:400px">';
                echo '<div class="card-body text-info">';
                    echo "<h4 class='card-title'>{$registro->getNome()}</h4>";
                    echo "<div class='card-text'>";
                        echo "<p>{$registro->getDescricao()}</p>";
                        echo "<p><b>Data de Inicio:</b> {$registro->getDataInicio()}</p>";
                        echo "<p><b>Data de Finalização:</b> {$registro->getDataFinalizacao()}</p>";
                    echo "</div>";
                echo '</div>';
                echo '<div class="card-footer">';
                    echo "<button class='btn' data-toggle='modal' data-target='#modalAlterar' data-id='{$registro->getId()}' >";
                        echo "<img width='16' src='../img/edit-regular.svg' alt=''>";
                    echo "</button>";
                    echo "<a class='btn ' href='../Control/ProjetoControl.php?acao=2&id=".$registro->getId()."'><img width='16' src='../img/trash-alt-solid.svg' alt=''></a>";
                echo '</div>';
            echo '</div>';
        }
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
<?php
    include 'rodape.php';
?>
