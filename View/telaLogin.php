<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/styleLogin.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/modal.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/formModal.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/select.css"/>
</head>
<body>
<img src="../img/login/poster.png" class="poster">
<img src="../img/login/estante.png" class="estante">
<img src="../img/login/monitor.png" class="monitor">
<img src="../img/login/celular.png" class="celular">
<div class="container-logo">
    <img src="../img/login/logo.png" class="logo">
</div>
<div class="login">
    <form action="/acaoUsuario" method="post" class="form-login">
        <?php Mensagem::exibir('danger');?>
        <div class="form-group">
            <label for="inputUsuario" class="form-label">Usuário</label>
            <input type="text" name="nomeUsuario" id="inputUsuario" class="form-input">
        </div>
        <div class="form-group">
            <label for="inputSenha" class="form-label">Senha</label>
            <input class="form-input" type="password" name="senha" id="inputSenha">
        </div>
        <button type="button" class="link-login abre-modal">Registre-se</button>

        <input type="hidden" name="acao" value="logar">
        <input type="submit" class="btn-login" value="Login" >
    </form>
</div>

<div class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="btn-close">&times;</span>
            <h1>Cadastro de Usuários</h1>
        </div>
        <form action="/acaoUsuario" method="post" class="form-modal">
            <div class="modal-group">
                <label for="nome" class="modal-label">Nome Completo</label>
                <input type="text" class="modal-input" name="nome" id="nome" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="email" class="modal-label">E-mail</label>
                <input type="text" class="modal-input" name="email" id="email" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="usuario" class="modal-label">Nome de Usuário</label>
                <input type="text" class="modal-input" name="usuario" id="usuario" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="senha" class="modal-label">Senha</label>
                <input type="password" class="modal-input" name="senha" id="senha" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="dtNasc" class="modal-label">Dt. de Nascimento</label>
                <input type="text" class="modal-input" name="dtNasc" id="dtNasc" autocomplete="off">
            </div>

            <div class="modal-group">
                <label for="rg" class="modal-label">RG</label>
                <input type="text" class="modal-input" name="rg" id="rg" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="dtEmissao" class="modal-label">Dt. Emissão</label>
                <input type="text" class="modal-input" name="dtEmissao" id="dtEmissao" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="cpf" class="modal-label">CPF</label>
                <input type="text" class="modal-input" name="cpf" id="cpf" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="formacao" class="modal-label">Formação</label>
                <input type="text" class="modal-input" name="formacao" id="formacao" autocomplete="off">
            </div>
            <div class="modal-group">
                <label for="valorHora" class="modal-label">Valor Hora</label>
                <input type="text" class="modal-input" name="valorHora" id="valorHora" autocomplete="off">
            </div>

            <div class="container-select">
                <div class="modal-select">
                    <label for="back-select" class="select-label">Atuação</label>
                    <select class="custom-select" name="atuacao" id="back-select">
                        <option value="Colaborador" selected>Colaborador</option>
                        <option value="Terceiros">Terceiros</option>
                        <option value="Bolsista/Voluntário">Bolsista/Voluntário</option>
                    </select>
                </div>
            </div>
            <input type="hidden" value="1" name="tipo">
            <input type="hidden" value="cadastrarUsuario" name="acao">
            <button type="submit" class="modal-btn">Cadastrar</button>
        </form>
    </div>
</div>

<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/funcoesLogin.js"></script>
</body>
</html>