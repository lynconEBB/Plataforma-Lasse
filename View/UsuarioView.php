<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/reset.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleUsuario.css" />
</head>
<body>
    <main class="cont">
        <aside class="side-bar">
            <img src="../img/perfil.png" class="foto">
            <span class="side-bar-title"><?= $usuario->getLogin() ?></span>
            <span class="side-bar-info"><?= $usuario->getAtuacao() ?></span>
            <span class="side-bar-info"><?= $usuario->getFormacao() ?></span>

            <button class="side-bar-button" data-toggle='modal' data-target='#modalAlterar' data-nome="<?= $usuario->getNomeCompleto()?>"
            data-login="<?=$usuario->getLogin()?>" data-email="<?=$usuario->getEmail()?>" data-dtnasc="<?=$usuario->getDtNascimento()?>" data-rg="<?=$usuario->getRg()?>" data-cpf="<?=$usuario->getCpf()?>"
            data-dtemissao="<?=$usuario->getDtNascimento()?>" data-formacao="<?=$usuario->getFormacao()?>" data-valorhora="<?=$usuario->getValorHora()?>" data-atuacao="<?=$usuario->getAtuacao()?>">Alterar</button>

            <form action="/acaoUsuario" method="post">
                <input type="hidden" name="acao" value="sair">
                <button class="side-bar-button red">Sair</button>
            </form>

            <a href="/menu/atividadeNaoPlanejada"><button class="side-bar-button">Menu Atividades</button></a>
        </aside>
        <section class="perfil">
            <div class="perfil-collum">
                <div class="perfil-item">
                    <label class="item-label">Nome Completo:</label>
                    <span class="item-value"><?= $usuario->getNomeCompleto() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">CPF:</label>
                    <span class="item-value"><?= $usuario->getCpf() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">RG:</label>
                    <span class="item-value"><?= $usuario->getRg() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">Email:</label>
                    <span class="item-value"><?= $usuario->getEmail() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">Data de Nascimento:</label>
                    <span class="item-value"><?= $usuario->getDtNascimento() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">Valor da Hora:</label>
                    <span class="item-value">RS <?= $usuario->getValorHora() ?></span>
                </div>
                <div class="perfil-item">
                    <label class="item-label">Data de Emissao:</label>
                    <span class="item-value"><?= $usuario->getDtEmissao() ?></span>
                </div>
            </div>
            <div class="perfil-collum">
                <span class="side-bar-title">Projetos</span>
                <?php
                    if(count($projetos)>0){
                        foreach ($projetos as $projeto){
                            $projetoControl = new ProjetoControl();
                            echo '<span class="item-value">'. $projeto->getNome();
                            if($projetoControl->verificaDono($projeto->getId())){
                                echo '&#8902;';
                            }
                            echo '</span>';
                        }
                    }else{
                        echo '<label class="item-label">Nenhuma Projeto encontrado :(</label>';
                    }
                ?>
                <a href="/menu/projeto"><button class="side-bar-button green">Menu dos Projetos</button></a>
            </div>
        </section>
    </main>

    <div class="modal fade" id="modalAlterar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Alteração de Dados</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/acaoUsuario" method="post">
                        <div class="form-group">
                            <label for="nome" class="col-form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="nome" id="nome">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">E-mail</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="usuario" class="col-form-label">Nome de Usuário</label>
                            <input type="text" class="form-control" name="usuario" id="usuario">
                        </div>
                        <div class="form-group">
                            <label for="dtNasc" class="col-form-label">Dt. de Nascimento</label>
                            <input type="text" class="form-control" name="dtNasc" id="dtNasc">
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 ">
                                <label for="rg" class="col-form-label">RG</label>
                                <input type="text" class="form-control" name="rg" id="rg">
                            </div>
                            <div class="form-group col-sm-6 ">
                                <label for="dtEmissao" class="col-form-label">Dt. Emissão</label>
                                <input type="text" class="form-control" name="dtEmissao" id="dtEmissao">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cpf" class="col-form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="cpf">
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="formacao" class="col-form-label">Formação</label>
                                <input type="text" class="form-control" name="formacao" id="formacao">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="valorHora" class="col-form-label">Valor Hora</label>
                                <input type="text" class="form-control" name="valorHora" id="valorHora">
                            </div>
                        </div>

                        <div class=form-group">
                            <label for="atuacao" class="col-form-label-select">Atuação</label>
                            <select class="custom-select" name="atuacao" id="atuacao">
                                <option value="Colaborador">Colaborador</option>
                                <option value="Terceiros">Terceiros</option>
                                <option value="Bolsista/Voluntário">Bolsista/Voluntário</option>
                            </select>
                        </div>
                        <br>
                        <input type="hidden" value="1" name="tipo">
                        <input type="hidden" value="alterarUsuario" name="acao">
                        <button type="submit" class="btn btn-primary align-self-center">Alterar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/funcoesUsuario.js"></script>
</body>
</html>