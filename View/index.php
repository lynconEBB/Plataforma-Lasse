<?php
    include "cabecalho.php";
?>
    <form action="../Control/ValidacaoLogin.php" method="post">
        Usuário <input type="text" name="usuario"><br>
        Senha <input type="password" name="senha"><br>
        <input type="hidden" name="action" value="login">
        <input type="submit" value="Login">
    </form>
<?php
    include "rodape.php";
?>