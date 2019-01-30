<?php
    include 'cabecalho.php';
    require_once '../Control/ValidacaoLogin.php';

    if(ValidacaoLogin::verificar()==true) {
?>
        <a href="cadastroFuncionario.php">Cadastrar Novo Funcionario</a><br>
        <a href="formulario.php">Criar Formulario de Viagem</a><br>
        <a href="../Control/ValidacaoLogin.php?action=sair">Sair</a>
<?php
    }
    include 'rodape.php';
?>