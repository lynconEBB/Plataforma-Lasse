<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/grid-padrao.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/botoes.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleUsuario.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <header class="page-header">
            <span class="titulo-header">Perfil de Usuário</span>
    </header>


    <div class="side-bar-back">
        <aside class="side-bar">
            <article class="side-bar-start">

                <div class="container-foto">
                    <img src="../img/Icons/cat.jpg" class="foto-perfil" alt="Foto de Usuario">
                </div>
                <span class="side-bar-title"><?=$usuario->getLogin()?></span>

                <button class="side-bar-button" data-toggle="modal" data-target="#modalInfo">
                    <img src="../img/Icons/info.png" class="side-bar-icon" alt="Foto de Usuario">
                </button>
                <button class="side-bar-button" data-toggle='modal' data-target='#modalAlterar' data-nome="<?= $usuario->getNomeCompleto()?>"
                        data-login="<?=$usuario->getLogin()?>" data-email="<?=$usuario->getEmail()?>" data-dtnasc="<?=$usuario->getDtNascimento()?>" data-rg="<?=$usuario->getRg()?>" data-cpf="<?=$usuario->getCpf()?>"
                        data-dtemissao="<?=$usuario->getDtNascimento()?>" data-formacao="<?=$usuario->getFormacao()?>" data-valorhora="<?=$usuario->getValorHora()?>" data-atuacao="<?=$usuario->getAtuacao()?>">
                    <img src="../img/Icons/editarIcone.png" class="side-bar-icon" alt="Alterar dados do Usuario">
                </button>
                <img src="../img/Icons/grafico.png" class="side-bar-icon" alt="Exibir graficos de Usuário">
            </article>
            <article class="side-bar-middle">
                <a href="https://webmail.pti.org.br/">
                    <img src="../img/Icons/email.png" class="side-bar-icon" alt="Icone para levar ao webmail zimbra">
                </a>
                <a href="https://git.pti.org.br/users/sign_in">
                    <img src="../img/Icons/gitlab.png" class="side-bar-icon" alt="Icone para ir para site do GitLab">
                </a>
            </article>
            <article class="side-bar-end">
                <form action="/acaoUsuario" method="post">
                    <input type="hidden" name="acao" value="sair">
                    <button class="side-bar-button"><img src="../img/Icons/Sair.png" class="side-bar-icon" alt="Icone para sair do Sistema"></button>
                </form>
            </article>
        </aside>
    </div>

    <main class="main-content">
        <section class="menu">
            <header class="menu-header">
                Projetos
            </header>
            <div class="menu-body">
                <?php
                if(count($projetos)>0){
                    foreach ($projetos as $projeto){
                    $projetoControl = new ProjetoControl();
                    ?>
                    <div class="item-menu">
                        <?php if($projetoControl->verificaDono($projeto->getId())){
                            echo '<span class="item-img">&#8902;</span>';
                        } ?>
                        <span class="item-title"><?=$projeto->getNome()?></span>
                        <span class="item-detail">Data de Inicio: <?=$projeto->getDataInicio()?></span>
                        <span class="item-detail">Data de Finalização: <?=$projeto->getDataFinalizacao()?></span>
                    </div>
                <?php
                    }
                }else{
                ?>
                    <div class="alert">
                        <img src="../img/mascote/RaioSozinho.png" class="alert-img">
                        <span class="alert-text">Nenhum Projeto Encontrado</span>
                    </div>
                <?php
                }
                ?>
                <a href="/menu/projeto"><button class="btn-default">Ver Projetos</button></a>
            </div>
        </section>
        <section class="menu">
            <header class="menu-header">
                Atividades
            </header>
            <div class="menu-body">
                    <?php
                    if(count($atividades)>0){
                        foreach ($atividades as $atividade){
                            $projetoControl = new ProjetoControl();
                            ?>
                            <div class="item-menu">
                                <span class="item-title"><?=$atividade->getTipo()?></span>
                                <span class="item-detail">Tempo Gasto: <?=$atividade->getTempoGasto()?></span>
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="alert">
                            <img src="../img/mascote/RaioSozinho.png" class="alert-img">
                            <span class="alert-text">Nenhuma Atividade Encontrado</span>
                        </div>
                        <?php
                    }
                    ?>
                <a href="/menu/atividadeNaoPlanejada"><button class="btn-default">Menu Atividades</button></a>
            </div>
        </section>
        <section class="menu">
            <header class="menu-header">
                Formulários
            </header>
            <div class="menu-body">
                <div class="alert">
                    <img src="../img/mascote/RaioSozinho.png" class="alert-img">
                    <span class="alert-text">Nenhum Formulário Encontrado</span>
                </div>
            </div>
        </section>
    </main>
<!----------------------------------------------Modais---------------------------------------------------------------->
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

    <div class="modal fade" id="modalInfo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Informações de Usuario</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="container-info">
                        <img src="../img/Icons/cat.jpg" class="foto-perfil" alt="Foto de Usuario">
                    </div>
                    <div class="info-item">
                        <label class="item-label">Nome Completo:</label>
                        <span class="item-value"><?= $usuario->getNomeCompleto() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">CPF:</label>
                        <span class="item-value"><?= $usuario->getCpf() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">RG:</label>
                        <span class="item-value"><?= $usuario->getRg() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">Email:</label>
                        <span class="item-value"><?= $usuario->getEmail() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">Data de Nascimento:</label>
                        <span class="item-value"><?= $usuario->getDtNascimento() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">Valor da Hora:</label>
                        <span class="item-value">RS <?= $usuario->getValorHora() ?></span>
                    </div>
                    <div class="info-item">
                        <label class="item-label">Data de Emissao:</label>
                        <span class="item-value"><?= $usuario->getDtEmissao() ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/funcoesUsuario.js"></script>
</body>
</html>