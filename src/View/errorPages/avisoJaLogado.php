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
    <div class="login">
        <h1>Você ja esta logado no sistema como <?php echo $_SESSION['usuario']?></h1>
        <p><a href="../ProjetoView.php">Clique para acessar o sistema</a></p>
        <form action="/acaoUsuario" method="post">
            <input type="hidden" name="acao" value="sair">
            <button class="btn btn-danger">Sair</button>
        </form>
    </div>
</body>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/funcoesLogin.js"></script>
</html>
