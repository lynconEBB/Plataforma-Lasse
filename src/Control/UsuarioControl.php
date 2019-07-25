<?php

namespace Lasse\LPM\Control;

use Exception;
use InvalidArgumentException;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\PdoFactory;
use Lasse\LPM\Services\Validacao;
use PDO;

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


    /**
     * Cria um Objeto Usuario
     * cadastra no banco de dados usando o Objeto Criado
     * Retorna para a tela de Login
     */
    protected function cadastrar(){
        session_start();
        try{
            Validacao::validar('Senha',$_POST['senha'],'semEspaco','obrigatorio','texto',['minimo',6]);
            if(!$this->DAO->listarPorLogin($_POST['usuario'])){
                $hash = password_hash($_POST['senha'],PASSWORD_BCRYPT);
                $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],$hash,$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['tipo'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora']);
                $this->DAO->cadastrar($usuario);
                header('Location: /login');
                die();
            }else {
                throw new Exception("Nome de Usuário ja utilizado");
            }
        }catch (Exception $argumentException) {
            $_SESSION['danger'] = $argumentException->getMessage();
            header("Location: /login");
            die();
        }


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
        return $this->DAO->listarPorId($id);
    }

    public function tentaLogar()
    {
        session_start();
        if ($_POST["nomeUsuario"] != "" && $_POST["senha"] != "") {
            $login = $_POST["nomeUsuario"];
            $senha = $_POST["senha"];

            if ($usuario = $this->DAO->listarPorLogin($login)) {
                if( password_verify($senha,$usuario->getSenha())){
                    $_SESSION["usuario-id"] = $usuario->getId();
                    $_SESSION["usuario"] = $_POST["nomeUsuario"];
                    $_SESSION["usuario-classe"] = $usuario;
                    $_SESSION["autenticado"] = TRUE;

                    header("Location: /menu/usuario");
                    die();
                }else{
                    $_SESSION['danger'] = "Senha errada :(";
                    header("Location: /login");
                    die();
                }
            } else {
                $_SESSION['danger'] = "Usuário não registrado :(";
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
                    require '../View/telaLogin.php';
                }

                break;
            case 'perfil':
                self::verificar();
                $usuario = $this->listarPorId($_SESSION['usuario-id']);

                $projetoControl = new ProjetoControl();
                $projetos = $projetoControl->listarPorIdUsuario($_SESSION['usuario-id']);

                $atividadeControl = new AtividadeControl();
                $atividades = $atividadeControl->listarPorIdUsuario($_SESSION['usuario-id']);
                require '../View/telaUsuario.php';
                break;
            case 'teste':
                require '../View/telaProjetos.php';
        }
    }
}