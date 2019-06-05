<?php
  require_once '../Services/UsuarioDao';
  session_start();
  $usuario = $_SESSION['UsuarioModel'];
  $funcDAO = new UsuarioDao();
  $dados = $funcDAO->listarPorUsuario();
?>
Nome Completo:<input type="text" name="nome" value="<?php echo $dados->getNomeCompleto(); ?>"><br>
RG:<input type="text" name="rg" value="<?php echo $dados->getRg(); ?>"><br>
Data de Emissão: <input type="date" name="dataEm" value="<?php echo $dados->getDtEmissao(); ?>"><br>
CPF: <input type="text" name="cpf" value="<?php echo $dados->getCpf(); ?>"><br>
Data de Nascimento <input type="date" name="dtNascimento" value="<?php echo $dados->getDtNascimento(); ?>"><br>
<label for="tipo">Tipo de Funcionario</label>
<select name="tipo" id="tipo">
<?php
    if($dados->getTipo() == "Colaborador"){
        echo '<option value="Colaborador" selected>Colaborador</option>';
        echo '<option value="Terceiros">Terceiros</option>';
        echo '<option value="Bolsista/Voluntário">Bolsista/Voluntário</option>';
    }elseif ($dados->getTipo() == "Terceiros"){
        echo '<option value="Colaborador">Colaborador</option>';
        echo '<option value="Terceiros" selected>Terceiros</option>';
        echo '<option value="Bolsista/Voluntário">Bolsista/Voluntário</option>';
    }elseif ($dados->getTipo() == "Bolsista/Voluntário"){
        echo '<option value="Colaborador">Colaborador</option>';
        echo '<option value="Terceiros">Terceiros</option>';
        echo '<option value="Bolsista/Voluntário" selected>Bolsista/Voluntário</option>';
    }
?>
</select>
