<?php

namespace Lasse\LPM\Control;

use Exception;
use InvalidArgumentException;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\Mensagem;
use Lasse\LPM\Services\PdoFactory;
use Lasse\LPM\Services\Validacao;
use PDO;
use PDOException;
use stdClass;
use Firebase\JWT\JWT;

class UsuarioControl {

    private $metodo;
    private $url;

    public function __construct($url){
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $url;
        $this->DAO = new UsuarioDao();
        $this->processaRequisicao();
    }

    /**
     * Cria um Objeto Usuario
     * cadastra no banco de dados usando o Objeto Criado
     * @param stdClass $info
     */
    protected function cadastrar($info){
        try{
            Validacao::validar('Senha',$info->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
            if(!$this->DAO->listarPorLogin($info->login)){
                $hash = password_hash($info->senha,PASSWORD_BCRYPT);
                $usuario = new UsuarioModel($info->nomeCompleto,$info->login,$hash,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao,$info->valorHora);
                $this->DAO->cadastrar($usuario);
                http_response_code(200);
                echo json_encode(array("message" => "Usuario Registrado com Sucesso!"));
            }else {
                http_response_code(400);
                echo json_encode(array("message" => "Nome de Usuário ja utilizado"));
            }
        }catch (PDOException $pdoErro){
            http_response_code(400);
            echo json_encode(array("message" => "Nome de Usuário ja utilizado"));
        }catch (Exception $argumentException) {
            http_response_code(400);
            echo json_encode(array("message" => "Nome de Usuário ja utilizado"));
        }
    }

    protected function excluir(int $id){
        try{
            $projetoControl = new ProjetoControl();
            $projetos = $projetoControl->listarPorIdUsuario($id);
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId())) {
                    throw new Exception("Você não pode excluir sua conta sendo dono de um projeto.<br> Exclua seus projetos ou transfira o dominio para outro funcionário");
                }
            }
            $this->DAO->excluir($id);
            $this->deslogar();
        }catch (PDOException $excecao){
            $_SESSION['danger'] = 'Erro durante exclusão no banco de dados.';
            header('Location: /login');
            die();
        }catch (Exception $excecao){
            $_SESSION['danger'] = $excecao->getMessage();
            header('Location: /menu/usuario');
            die();
        }
    }

    public function listar() {
        try{
            self::autenticar();
            //return $this->DAO->listar();
        }catch (PDOException $excecao){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /erro');
            die();
        }
    }

    protected function atualizar(){
        try{
            session_start();
            $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],null,$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora'],$_SESSION['usuario-id']);
            $this->DAO->alterar($usuario);
            $_SESSION['danger'] = 'Dados alterados com sucesso!';
            header('Location: /menu/usuario');
        }catch (PDOException $excecao){
            $_SESSION['danger'] = 'Erro durante alteração no banco de dados.';
            header('Location: /erro');
            die();
        }

    }

    public function listarPorId($id){
        try{
            return $this->DAO->listarPorId($id);
        }catch (PDOException $excecao){
            $_SESSION['danger'] = 'Erro durante seleção no banco de dados.';
            header('Location: /erro');
            die();
        }
    }

    public function tentaLogar($info)
    {
        try{
            if ($info->login != "" && $info->senha != "") {
                $login = $info->login;
                $senha = $info->senha;

                if ($usuario = $this->DAO->listarPorLogin($login)) {
                    if( password_verify($senha,$usuario->getSenha())){
                        $secret_key = "SUPERSENHA123";
                        $issuedat_claim = time();
                        $notbefore_claim = $issuedat_claim;
                        $expire_claim = $issuedat_claim + 60;
                        $token = array(
                            "iss" => "Lasse-Project-Manager",  // Fornecedor da API
                            "aud" => "All",                    // Audiencia da API
                            "iat" => $issuedat_claim,          // Horario em que o token foi criado
                            "nbf" => $notbefore_claim,         // Tempo ate o token ser valido
                            "exp" => $expire_claim,            // Tempo ate o token expirar
                            "data" => array(                   // Informações que gerarão o token
                                "id" => $usuario->getId(),
                                "login" => $usuario->getLogin(),
                                "email" => $usuario->getEmail(),
                            ));

                        http_response_code(200);

                        $jwt = JWT::encode($token, $secret_key);
                        header("Set-Cookie: token={$jwt}");
                        echo json_encode(array("message" => "Logado com Sucesso"));

                    }else{
                        throw new Exception("Senha errada :(");
                    }
                } else {
                    throw new Exception("Usuário não registrado :(");
                }
            } else {
                throw new Exception("Os Campos devem ser preenchidos :X");
            }
        }catch (Exception $excecao){
            http_response_code(400);
            echo json_encode(array("message" => $excecao->getMessage()));
        }
    }

    public static function autenticar()
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            echo $_SERVER['HTTP_AUTHORIZATION'];
        }
    }

    public function processaRequisicao()
    {
        switch ($this->metodo) {
            case 'POST':
                $info = json_decode(file_get_contents("php://input"));
                if (isset($this->url[2]) && $this->url[2] == 'login') {
                    $this->tentaLogar($info);
                } else {
                    $this->cadastrar($info);
                }
                break;
            case 'GET':
                $this->listar();
                break;
            case 'PUT':
                break;
            case 'DELETE':
                break;
        }
    }
}