<?php
    include 'cabecalho.php';
?>
    <form action="../Control/FormularioControl.php" method="post" enctype="multipart/form-data">
        <input type="file" name="arquivo" id="arquivo">
        <input type="submit" value="Vizualizar">

    </form>
<?php
    include 'rodape.php';
?>
