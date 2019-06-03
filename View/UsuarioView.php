<?php
    require_once '../Services/Autoload.php';
    LoginControl::verificar();

    require_once "cabecalho.php";

    $usuarioControl = new UsuarioControl();
?>