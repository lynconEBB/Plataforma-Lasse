<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <title>Lasse Project Manager</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/styleSheet.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">

</head>
<body>

<header>
    <video autoplay loop id="background" src="../img/videoplayback.mp4" poster="../img/poster.jpg"></video>
</header>
<div id="img-back">

</div>
<section id="cover">
        <div class="container" >
            <div class="a"></div>
            <div class="row text-light">
                <div class="col-sm-6 offset-sm-3 text-center" id="login">
                    <h1>Login de Usuários</h1>
                    <form action="../Control/ValidacaoLogin.php" method="post" >
                        <div class="form-group">
                            <label for="inputUsuario" class="float-left texto-login">Nome de usuário ou E-mail</label>
                            <input type="text" name="usuario-email" id="inputUsuario" class="form-control" placeholder="exemplo@pti.org.br"><br>
                        </div>
                        <div class="form-group ">
                            <label for="inputSenha" class="texto-login float-left">Senha</label>
                            <input class="form-control" type="password" name="senha" id="inputSenha" placeholder="senha,123"><br>
                        </div>
                        <input type="hidden" name="action" value="login">
                        <input type="submit" class="btn btn-dark rounded-bottom" value="Login">
                    </form>
                </div>
            </div>
        </div>
</section>

</body>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>

</html>