<?php
    include 'cabecalho.php';
    require_once '../DAO/FuncionarioDAO.php';
    $funcDAO = new FuncionarioDAO();
    $funcionarios = $funcDAO->listar();
?>
    <table border="5">
        <tr>
            <th>ID</th>
            <th>Nome de Usuario</th>
            <th>Nome Completo</th>
            <th>Data de Nascimento</th>
            <th>CPF</th>
            <th>RG</th>
            <th>Data de Emissão</th>
            <th>Tipo de Funcionario</th>
            <th>E-mail</th>
            <th>Opção</th>
        </tr>
        <?php
            foreach ($funcionarios as $funcionario){
                echo '<tr>';
                    echo "<td>".$funcionario->getId()."</td>";
                    echo "<td>".$funcionario->getUsuario()."</td>";
                    echo "<td>".$funcionario->getNomeCompleto()."</td>";
                    echo "<td>".$funcionario->getDtNascimento()."</td>";
                    echo "<td>".$funcionario->getCpf()."</td>";
                    echo "<td>".$funcionario->getRg()."</td>";
                    echo "<td>".$funcionario->getDtEmissao()."</td>";
                    echo "<td>".$funcionario->getTipo()."</td>";
                    echo "<td>".$funcionario->getEmail()."</td>";
                    echo "<td><a href='alteracaoFuncionario.php?id=".$funcionario->getId()."'>Alterar</a>|<a href='../Control/FuncionarioControl.php?id=".$funcionario->getId()."&acao=3'>Excluir</a></td>";
                echo '</tr>';
            }
        ?>
    </table>
<?php
    include 'rodape.php';
?>
