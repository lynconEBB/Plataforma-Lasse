<?php
    include 'cabecalho.php';
?>

<div class="container">
    <div class="row">
        <form action="../../Control/FuncionarioControl.php" method="post" class="ml-auto mr-auto mt-5">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" class="form-control" name="nome" id="nome">
            </div>
            <div class="form-group">
                <label for="email" >E-mail</label>
                <input type="text" class="form-control" name="email" id="email">
            </div>
            <div class="form-group">
                <label for="usuario">Nome de Usuário</label>
                <input type="text" class="form-control" name="usuario" id="usuario">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha">

            </div>
            <div class="form-group">
                <label for="dtNasc">Data de Nascimento</label>
                <input type="date" class="form-control" name="dtNasc" id="dtNasc">

            </div>
            <div class="row">
                <div class="form-group col-sm-7">
                    <label for="rg">RG</label>
                    <input type="text" class="form-control" name="rg" id="rg">
                </div>
                <div class="form-group col-sm-5">
                    <label for="dtEmissao">Data de Emissão</label>
                    <input type="date" class="form-control" name="dtEmissao" id="dtEmissao">
                </div>
            </div>
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control" name="cpf" id="cpf">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-7">
                        <label for="formacao">Formação</label>
                        <input type="text" class="form-control" name="formacao" id="formacao">
                    </div>
                    <div class="col-sm-5">
                        <label for="valorHora">Valor da Hora</label>
                        <input type="text" class="form-control" name="valorHora" id="valorHora" >

                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="atuacao">Atuação</label>
                <select name="atuacao" class="custom-select" id="atuacao">
                    <option value="Colaborador" selected>Colaborador</option>
                    <option value="Terceiros">Terceiros</option>
                    <option value="Bolsista/Voluntário">Bolsista/Voluntário</option>
                </select>
            </div>
            <input type="hidden" value="1" name="tipo">
            <input type="hidden" value="1" name="acao">
            <input type="submit" value="Cadastrar" class="btn btn-danger">
        </form>
    </div>
</div>

<?php
    include 'rodape.php';
?>
