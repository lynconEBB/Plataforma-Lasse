
<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lasse Project Manager</title>
    <link rel="stylesheet" type="text/css" href="css/styleSheet.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
<?php
    if(isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == TRUE) {
?>
    <div class="login">
        <h1>Você ja esta logado no sistema como <?php echo $_SESSION['usuario']?></h1>
        <p><a href="ProjetoView.php">Clique para acessar o sistema</a></p>
        <form action="/acaoUsuario" method="post">
            <input type="hidden" name="acao" value="sair">
            <button class="btn btn-danger">Sair</button>
        </form>
    </div>

<?php
    }else {
?>
    <div class="container">
        <video autoplay loop poster="img/poster.jpg">
            <source src="img/videoplayback.mp4" type="video/mp4">
            <source src="img/videoplayback.webm" type="video/webm">
        </video>
        <div class="row">
            <nav>
                <a class="link-nav" href="#">Redes Socias</a>
                <img class="navbar-brand logo" src="img/logo.png" alt="Logotipo Lasse">
                <a class="link-nav" href="#">Sobre</a>
            </nav>
        </div>
        <div class="container">
            <img class="detalhes detalhe-direito" src="img/canto2.png" alt="Detalhes Template">
            <img class="detalhes detalhe-esquerdo" src="img/canto1.png" alt="Detalhes Template">
        </div>
        <div class="login">
            <form action="/acaoUsuario" method="post">
                <?php
                    Mensagem::exibir('danger');
                ?>
                <div class="form-group-login">
                    <label for="inputUsuario" class="form-label">Nome de usuário ou E-mail</label>
                    <input type="text" name="nomeUsuario" id="inputUsuario" class="form-input">
                </div>

                <div class="form-group-login">
                    <label for="inputSenha" class="form-label">Senha</label>
                    <input class="form-input" type="password" name="senha" id="inputSenha">
                </div>

                <input type="hidden" name="acao" value="logar">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCadastro">
                    Registrar-se
                </button>
                <input type="submit" class="btn btn-dark rounded-bottom" value="Login">
            </form>
        </div>

        <div class="modal fade" id="modalCadastro" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Registro de Novo Funcionário</h5>
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
                                <label for="senha" class="col-form-label">Senha</label>
                                <input type="password" class="form-control" name="senha" id="senha">
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
                                    <option value="Colaborador" selected>Colaborador</option>
                                    <option value="Terceiros">Terceiros</option>
                                    <option value="Bolsista/Voluntário">Bolsista/Voluntário</option>
                                </select>
                            </div>
                            <br>
                            <input type="hidden" value="1" name="tipo">
                            <input type="hidden" value="cadastrarUsuario" name="acao">
                            <button type="submit" class="btn btn-primary align-self-center">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/funcoesLogin.js"></script>

    </html>
<?php
    }
?>