<?php

require_once '../Services/Autoload.php';

class LoginControl{
    private $login;
    private $senha;
    
    public function __construct(){
        session_start();
        $funcDAO = new FuncionarioDao();
        if (isset($_POST["acao"]) and $_POST["acao"] == "login") {
            if ($_POST["usuario-email"] != "" && $_POST["senha"] != "") {
                $this->login = $_POST["usuario-email"];
                $this->senha = $_POST["senha"];

                if ($funcDAO->consultar($this->login, $this->senha)) {
                    $usuario = $funcDAO->listarPorLogin($_POST["usuario-email"]);
                    $_SESSION["usuario-id"] = $usuario->getId();
                    $_SESSION["usuario"] = $_POST["usuario-email"];
                    $_SESSION["autenticado"] = TRUE;
                    header("Location: ../View/ProjetoView.php");
                    die();

                } else {
                    $_SESSION['danger'] = "Usuário ou Senha Incorretos :(";
                    header("Location: ../View/LoginView.php");
                    die();
                }
            } else {
                $_SESSION['danger'] = "Os Campos devem ser preenchidos :X";
                header("Location: ../View/LoginView.php");
                die();
            }
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

    public static function verificar(){
        if(isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == TRUE){
            return true;
        }
        else{
            $_SESSION['danger'] = "Logue no Sistema para ter acesso as funcionalidades!";
            header("Location: ../View/LoginView.php");
            die();
        }
    }
}
new LoginControl();
