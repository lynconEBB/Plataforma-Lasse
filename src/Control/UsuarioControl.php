<?php

namespace Lasse\LPM\Control;

use Exception;
use Firebase\JWT\SignatureInvalidException;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\Validacao;
use Firebase\JWT\JWT;

class UsuarioControl {

    private $metodo;
    private $url;
    private $DAO;

    public function __construct($url){
        header('Content-Type: application/json');
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $url;
        $this->DAO = new UsuarioDao();
        $this->processaRequisicao();
    }

    /**
     * Cria um Objeto Usuario
     * cadastra no banco de dados usando o Objeto Criado
     * @param $info
     * @throws Exception
     */
    protected function cadastrar($info){
        Validacao::validar('Senha',$info->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
        if(!$this->DAO->listarPorLogin($info->login)){
            $hash = password_hash($info->senha,PASSWORD_BCRYPT);
            $usuario = new UsuarioModel($info->nomeCompleto,$info->login,$hash,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao,$info->valorHora);
            $this->DAO->cadastrar($usuario);
            http_response_code(200);
            echo json_encode(array("message" => "Usuario Registrado com Sucesso!"));
        }else {
            throw new Exception("Nome de Usuário já registrado");
        }
    }

    protected function excluir(int $id){
        $projetoControl = new ProjetoControl();
        $projetos = $projetoControl->listarPorIdUsuario($id);
        foreach ($projetos as $projeto){
            if ($projetoControl->verificaDono($projeto->getId())) {
                throw new Exception("Você não pode excluir sua conta sendo dono de um projeto.<br> Exclua seus projetos ou transfira o dominio para outro funcionário");
            }
        }
        $this->DAO->excluir($id);
        $this->deslogar();
    }

    public function listar() {
        self::autenticar();
        $usuarios = $this->DAO->listar();
        $resposta = "{[";
        http_response_code(200);
        foreach ($usuarios as $usuario) {
            $resposta .= $usuario->toJSON().",";
        }
        $resposta .= "]}";
        echo $resposta;
    }

    protected function atualizar(){
        session_start();
        $usuario = new UsuarioModel($_POST['nome'],$_POST['usuario'],null,$_POST['dtNasc'],$_POST['cpf'],$_POST['rg'],$_POST['dtEmissao'],$_POST['email'],$_POST['atuacao'],$_POST['formacao'],$_POST['valorHora'],$_SESSION['usuario-id']);
        $this->DAO->alterar($usuario);
        $_SESSION['danger'] = 'Dados alterados com sucesso!';
        header('Location: /menu/usuario');
    }

    public function listarPorId($id){
        return $this->DAO->listarPorId($id);
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

        http_response_code(200);
        $token = JWT::encode($token, $secret_key);
        return $token;
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
    }

    public static function autenticar()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth =  explode(' ',$_SERVER['HTTP_AUTHORIZATION']);
            if (count($auth) == 2) {
                $auth = $auth[1];
                $decoded = JWT::decode($auth,'SUPERSENHA123',array('HS256'));
            } else
                throw new SignatureInvalidException();
        } else
            throw new SignatureInvalidException();
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
                $this->atualizar();
                break;
            case 'DELETE':
                $this->excluir();
                $this->atualizar();
                break;
        }
    }
}