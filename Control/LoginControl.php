<?php

class LoginControl{
    private $login;
    private $senha;
    
    public function __construct(){
        session_start();
        $funcDAO = new UsuarioDao();
        if (isset($_POST["acao"]) and $_POST["acao"] == "login") {

        }
        elseif (isset($_POST["acao"]) and $_POST["acao"] == "sair") {
            $this->sair();
        }
    }

    public function sair(){
        session_destroy();
        session_start();
        header("Location: ../View/LoginView.php");
        die();
    }


}
new LoginControl();
