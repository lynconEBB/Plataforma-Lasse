<?php
    require_once '../Services/Autoload.php';
    LoginControl::verificar();

    $usuarioControl = new UsuarioControl();
    $usuario = $usuarioControl->listarPorId($_SESSION['usuario-id']);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Perfil Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/reset.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleUsuario.css" />
</head>
<body>
    <main class="cont">
        <aside class="side-bar">
            <img src="../img/perfil.png" class="foto">
            <span class="side-bar-title"><?= $usuario->getLogin() ?></span>
            <span class="side-bar-info"><?= $usuario->getAtuacao() ?></span>
            <span class="side-bar-info"><?= $usuario->getFormacao() ?></span>
            <button class="side-bar-button">Editar</button>
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
                    if(count($usuario->getProjetos())>0){
                        foreach ($usuario->getProjetos() as $projeto){
                            echo '<span class="item-value">'. $projeto->getNome().'</span>';
                        }
                    }else{
                        echo '<label class="item-label">Nenhuma Projeto encontrado :(</label>';
                    }

                ?>
            </div>
        </section>
    </main>

    <script src="../js/jquery.js"></script>
    <script src="../js/funcoesUsuario.js"></script>
</body>
</html>