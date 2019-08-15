<?php

namespace Lasse\LPM\Control;

use Exception;
use Firebase\JWT\SignatureInvalidException;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\Validacao;
use Firebase\JWT\JWT;

class UsuarioControl extends CrudControl {

    public function __construct($url){
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $url;
        $this->DAO = new UsuarioDao();
        $this->processaRequisicao();
    }

    public function processaRequisicao()
    {
        switch ($this->metodo) {
            case 'POST':
                $info = json_decode(@file_get_contents("php://input"));
                // /api/users/login
                if (isset($this->url[2]) && $this->url[2] == 'login' && count($this->url) == 3) {
                    $this->tentaLogar($info);
                }
                // /api/users
                elseif (count($this->url) == 2) {
                    $this->cadastrar($info);
                }
                break;
            case 'GET':
                // /api/users/{idUsuario}
                if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                    $this->listarPorId($this->url[2]);
                }
                // /api/users
                elseif (count($this->url) == 2) {
                    $this->listar();
                }
                break;
            case 'PUT':
                $info = json_decode(@file_get_contents("php://input"));
                if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                    $this->atualizar($info);
                }
                break;
            case 'DELETE':
                if (count($this->url) == 2) {
                    $this->excluir();
                }
                break;
        }
    }

    /*
     * Cria um Objeto Usuario
     * cadastra no banco de dados usando o Objeto Criado
     */
    protected function cadastrar($info){
        Validacao::validar('Senha',$info->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
        if(!$this->DAO->listarPorLogin($info->login)){
            $hash = password_hash($info->senha,PASSWORD_BCRYPT);
            $usuario = new UsuarioModel($info->nomeCompleto,$info->login,$hash,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao,$info->valorHora,null);
            $this->DAO->cadastrar($usuario);
            $this->respostaSucesso("Usuario Registrado com Sucesso!");
        }else {
            throw new Exception("Nome de Usuário já registrado");
        }
    }

    protected function excluir(){
        $userInfo = self::autenticar();
        $projetoControl = new ProjetoControl();
        $projetos = $projetoControl->listarPorIdUsuario($userInfo['id']);
        if ($projetos != false) {
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId())) {
                    throw new Exception("Você não pode excluir sua conta sendo dono de um projeto.Exclua seus projetos ou transfira o dominio para outro funcionário");
                }
            }
        }
        $this->DAO->excluir($userInfo['id']);
        $this->respostaSucesso("Usuario Excluido com sucesso",null,$userInfo);
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
        }
    }

    public function listar() {
        $userInfo = self::autenticar();
        $usuarios = $this->DAO->listar();
        $dados = array();
        foreach ($usuarios as $usuario) {
            $dados[] = $usuario->toArray();
        }
        $this->respostaSucesso("Listando todos Usuários do banco de dados",$dados,$userInfo);
    }

    protected function atualizar($info) {
        $userInfo = self::autenticar();
        $usuario = new UsuarioModel($info->nomeCompleto,$info->login,null,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao,$info->valorHora,$userInfo['id']);
        $this->DAO->alterar($usuario);
        $this->respostaSucesso("Dados de Usuário alterados com sucesso",null,$userInfo);
    }

    public function listarPorId($id){
        $userInfo = self::autenticar();
        $usuario = $this->DAO->listarPorId($id);
        $this->respostaSucesso("Listando Usuário.",$usuario->toArray(),$userInfo);
    }

    public function tentaLogar($info)
    {
        if ($info->login != "" && $info->senha != "") {
            $login = $info->login;
            $senha = $info->senha;
            if ($usuario = $this->DAO->listarPorLogin($login)) {
                if( password_verify($senha,$usuario->getSenha())){
                    $token = $this->criaToken($usuario);
                    header("Set-Cookie: token={$token}");
                    $this->respostaSucesso("Logado com Sucesso");
                }else{
                    throw new Exception("Senha errada :(");
                }
            } else {
                throw new Exception("Usuário não registrado :(");
            }
        } else {
            throw new Exception("Os Campos devem ser preenchidos :X");
        }
    }

    private function criaToken($usuario) {
        $secret_key = "SUPERSENHA123";
        $issuedat_claim = time();
        $notbefore_claim = $issuedat_claim;
        $expire_claim = $issuedat_claim + 86400;
        $token = array(
            "iss" => "Lasse-Project-Manager",    // Fornecedor da API
            "aud" => $_SERVER['HTTP_USER_AGENT'],// Audiencia da API
            "iat" => $issuedat_claim,            // Horario em que o token foi criado
            "nbf" => $notbefore_claim,           // Tempo ate o token ser valido
            "exp" => $expire_claim,              // Tempo ate o token expirar
            "data" => array(                     // Informações que gerarão o token
                "id" => $usuario->getId(),
                "login" => $usuario->getLogin(),
                "email" => $usuario->getEmail(),
            ));

        $token = JWT::encode($token, $secret_key);
        return $token;
    }

    public static function autenticar()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth =  explode(' ',$_SERVER['HTTP_AUTHORIZATION']);
            if (count($auth) == 2) {
                $auth = $auth[1];
                $decoded = JWT::decode($auth,'SUPERSENHA123',array('HS256'));
                $userInfo = ["id" => $decoded->data->id, "login" => $decoded->data->login];
                return $userInfo;
            } else
                throw new SignatureInvalidException();
        } else
            throw new SignatureInvalidException();
    }

}