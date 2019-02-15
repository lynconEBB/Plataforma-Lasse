<?php
/**
 * Created by PhpStorm.
 * User: Lyncon
 * Date: 28/01/2019
 * Time: 09:33
 */
require_once '../DAO/FuncionarioDAO.php';

class ValidacaoLogin{
    private $login;
    private $senha;
    
    public function __construct(){
        session_start();
        $funcDAO = new FuncionarioDAO();
        if (isset($_POST["action"]) and $_POST["action"] == "login") {
            if ($_POST["usuario"] != "" && $_POST["senha"] != "") {
                $this->login = $_POST["usuario"];
                $this->senha = $_POST["senha"];
                if ($funcDAO->consultar($this->login, $this->senha)) {
                    $_SESSION["usuario"] = $_POST["CAMPO_USUARIO"];
                    $_SESSION["autenticado"] = TRUE;
                    header("Location: ../View/menu.php");
                } else {
                    echo "Seu nome de usuario ou senha esta incorreto ou nao encontrado";
                }
            } else {
                echo "Os campos devem estar Preenchidos";
            }
        }
        if (isset($_GET["action"]) and $_GET["action"] == "sair") {
            $this->sair();
        }
    }

    public function sair(){
        session_destroy();
        header("Location: ../View/index.php");
        exit();
    }

    public static function verificar(){
        if(isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == TRUE){
            return true;
        }
        else{
            echo "Você precisa estar Logado para utilzar o site<br>";
            echo "Por gentileza, faça o seu login <A href='../View/index.php'>clicando aqui</A>.";
            exit();
        }
    }
}
new ValidacaoLogin();
