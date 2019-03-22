<?php
    include 'cabecalho.php';
    require_once '../Control/ValidacaoLogin.php';

    if(ValidacaoLogin::verificar()==true) {
?>
        <a href="Funcionário/cadastroFuncionario.php">Cadastrar Novo Funcionario</a><br>
        <a href="Viagem/CadastroViagem.php">Criar Formulario de Viagem</a><br>
        <a href="../Control/ValidacaoLogin.php?action=sair">Sair</a>
<?php
    }
    include 'rodape.php';
?>