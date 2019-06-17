<?php

class UsuarioControl extends CrudControl {

    public function __construct(){
        $this->DAO = new UsuarioDao();
        parent::__construct();
    }

    public function defineAcao($acao){
        switch ($acao){
            case 'cadastrarUsuario':
                $this->cadastrar();
                break;
            case 'excluirUsuario':
                $this->excluir($_POST['id']);
                break;
            case 'alterarUsuario':
                $this->atualizar();
                break;
            case 'logar':
                $this->tentaLogar();
                break;
            case 'sair':
                $this->deslogar();
                break;
        }
    }

    /*
     * Cria um Objeto Usuario
     * cadastra no banco de dados usando o Objeto Criado
     * Retorna para a tela de Login
     */
    protected function cadastrar(){
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],$_POST['senha'],$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora']);
        $this->DAO->cadastrar($usuario);

        header('Location: /login');
        die();
    }

    protected function excluir(int $id){
        $this -> DAO -> excluir($id);

        header('Location: /login');
        die();
    }

    public function listar() {
        return $this ->DAO -> listar();
    }

    protected function atualizar(){
        session_start();
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],null,$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora'],$_SESSION['usuario-id']);
        $this -> DAO ->alterar($usuario);

        header('Location: /menu/usuario');
    }

    public function listarPorId($id){
        $this->DAO->listarPorId($id);
    }

    public function tentaLogar()
    {
        session_start();
        if ($_POST["nomeUsuario"] != "" && $_POST["senha"] != "") {
            $login = $_POST["nomeUsuario"];
            $senha = $_POST["senha"];

            if ($this->DAO->consultar($login, $senha)) {

                $usuario = $this->DAO->listarPorLogin($_POST["nomeUsuario"]);
                $_SESSION["usuario-id"] = $usuario->getId();
                $_SESSION["usuario"] = $_POST["nomeUsuario"];
                $_SESSION["usuario-classe"] = $usuario;
                $_SESSION["autenticado"] = TRUE;
                header("Location: /menu/usuario");
                die();

            } else {
                $_SESSION['danger'] = "Usuário ou Senha Incorretos :(";
                header("Location: /login");
                die();
            }
        } else {
            $_SESSION['danger'] = "Os Campos devem ser preenchidos :X";
            header("Location: /login");
            die();
        }
    }
    public function deslogar(){
        session_start();
        session_destroy();
        header("Location: /login");
        die();
    }

    public static function verificar(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == TRUE){
            return true;
        }
        else{
            $_SESSION['danger'] = "Logue no Sistema para ter acesso as funcionalidades!";
            header("Location: /login");
            die();
        }
    }

    public function processaRequisicao(string $parametro)
    {
        switch ($parametro){
            case 'login':
                session_start();
                if(isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] == TRUE) {
                    require '../View/errorPages/avisoJaLogado.php';
                }else{
                    require '../View/LoginView.php';
                }

                break;
            case 'perfil':
                self::verificar();
                $this->listarPorId($_SESSION['usuario-id']);
                //require '../View/UsuarioView.php';
        }
    }
}