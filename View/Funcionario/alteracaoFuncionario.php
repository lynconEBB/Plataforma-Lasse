<?php
    include 'cabecalho.php';
    require_once '../Services/FuncionarioDAO.php';
    $funcDAO = new FuncionarioDAO();
    $funcionario = $funcDAO->listarPorId($_GET['id']);
?>
    <form action="../../Control/FuncionarioControl.php" method="post">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome" value="<?php echo $funcionario->getNomeCompleto() ?>"><br>
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email" value="<?php echo $funcionario->getEmail() ?>"><br>
        <label for="usuario">Nome de Usuário</label>
        <input type="text" name="usuario" id="usuario" value="<?php echo $funcionario->getUsuario() ?>"><br>
        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" value="<?php echo $funcionario->getSenha() ?>"><br>
        <label for="dtNasc">Data de Nascimento</label>
        <input type="date" name="dtNasc" id="dtNasc" value="<?php echo $funcionario->getDtNascimento() ?>"><br>
        <label for="rg">RG</label>
        <input type="text" name="rg" id="rg" value="<?php echo $funcionario->getRg() ?>"><br>
        <label for="dtEmissao">Data de Emissão</label>
        <input type="date" name="dtEmissao" id="dtEmissao" value="<?php echo $funcionario->getDtEmissao() ?>"><br>
        <label for="cpf">CPF</label>
        <input type="text" name="cpf" id="cpf" value="<?php echo $funcionario->getCpf() ?>"><br>
        <label for="tipo">Tipo de Funcionario</label>
        <select name="tipo" id="tipo">
            <?php
                if($funcionario->getTipo() == "Colaborador"){
                    echo '<option value="Colaborador" selected>Colaborador</option>';
                    echo '<option value="Terceiros">Terceiros</option>';
                    echo '<option value="Bolsista/Voluntário">Bolsista/Voluntário</option>';
                }elseif ($funcionario->getTipo() == "Terceiros"){
                    echo '<option value="Colaborador">Colaborador</option>';
                    echo '<option value="Terceiros" selected>Terceiros</option>';
                    echo '<option value="Bolsista/Voluntário">Bolsista/Voluntário</option>';
                }elseif ($funcionario->getTipo() == "Bolsista/Voluntário"){
                    echo '<option value="Colaborador">Colaborador</option>';
                    echo '<option value="Terceiros">Terceiros</option>';
                    echo '<option value="Bolsista/Voluntário" selected>Bolsista/Voluntário</option>';
                }
            ?>
        </select><br>
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id">
        <input type="hidden" value="2" name="acao">
        <input type="submit" value="Alterar">
    </form>
    


<?php
    include 'rodape.php';
?>
