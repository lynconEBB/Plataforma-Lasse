<?php
    include 'cabecalho.php';
?>
    <form action="../../Control/FuncionarioControl.php" method="post">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome"><br>
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email"><br>
        <label for="usuario">Nome de Usuário</label>
        <input type="text" name="usuario" id="usuario"><br>
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha"><br>
        <label for="dtNasc">Data de Nascimento</label>
        <input type="date" name="dtNasc" id="dtNasc"><br>
        <label for="rg">RG</label>
        <input type="text" name="rg" id="rg"><br>
        <label for="dtEmissao">Data de Emissão</label>
        <input type="date" name="dtEmissao" id="dtEmissao"><br>
        <label for="cpf">CPF</label>
        <input type="text" name="cpf" id="cpf"><br>
        <label for="tipo">Tipo de Funcionario</label>
        <select name="tipo" id="tipo">
            <option value="Colaborador">Colaborador</option>
            <option value="Terceiros">Terceiros</option>
            <option value="Bolsista/Voluntário">Bolsista/Voluntário</option>
        </select><br>
        <input type="hidden" value="1" name="acao">
        <input type="submit" value="Cadastrar">
    </form>
<?php
    include 'rodape.php';
?>
