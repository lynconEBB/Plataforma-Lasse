<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lasse Project Manager</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/styleSheet.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">

</head>
<body>
<div class="container">
    <video autoplay loop poster="../img/poster.jpg">
        <source src="../img/videoplayback.mp4" type="video/mp4">
        <source src="../img/videoplayback.webm" type="video/webm">
    </video>
    <div class="row">
        <nav>
            <a class="link-nav" href="#">Redes Socias</a>
            <img class="navbar-brand logo" src="../img/logo.png" alt="Logotipo Lasse">
            <a class="link-nav" href="#">Sobre</a>
        </nav>
    </div>
    <div class="container">
        <img class="detalhes detalhe-direito" src="../img/canto2.png" alt="Detalhes Template">
        <img class="detalhes detalhe-esquerdo" src="../img/canto1.png" alt="Detalhes Template">
    </div>
    <div class="login">
        <form action="../Control/ValidacaoLogin.php" method="post" >

            <div class="form-group">
                <label for="inputUsuario" class="form-label">Nome de usuário ou E-mail</label>
                <input type="text" name="usuario-email" id="inputUsuario" class="form-input" ><br>
            </div>

            <div class="form-group ">
                <label for="inputSenha" class="form-label">Senha</label>
                <input class="form-input" type="password" name="senha" id="inputSenha" ><br>
            </div>

            <div class="form-group">
                <label class="form-label" for="first">What is your name?</label>
                <input id="first" class="form-input" type="text" />
            </div>

            <input type="hidden" name="action" value="login">
            <input type="submit" class="btn btn-dark rounded-bottom" value="Login">
        </form>
    </div>
    
</div>
</body>

<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../bootstrap/js/funcoes.js"></script>

</html>
