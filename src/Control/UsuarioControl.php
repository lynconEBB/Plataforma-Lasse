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
        $this->DAO = new UsuarioDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            switch ($this->metodo) {
                case 'POST':
                    // /api/users/login
                    if (isset($this->url[2]) && $this->url[2] == 'login' && count($this->url) == 3) {
                        $info = json_decode(@file_get_contents("php://input"));
                        $token = $this->logar($info);
                        $this->respostaSucesso("Logado com Sucesso",$token);
                    } // /api/users
                    elseif (count($this->url) == 2) {
                        if (isset($_POST['dados'])) {
                            $info = json_decode($_POST['dados']);
                            $this->cadastrar($info);
                            $this->respostaSucesso("Usuario Registrado com Sucesso!",null,null);
                        } else {
                            throw new Exception("Parâmetros insuficientes ou mal estruturados");
                        }
                    }
                    break;
                case 'GET':
                    // /api/users/{idUsuario}
                    if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                        $usuario = $this->listarPorId($this->url[2]);
                        $this->respostaSucesso("Listando Usuário.",$usuario,$this->requisitor);
                    } // /api/users
                    elseif (count($this->url) == 2) {
                        $this->requisitor = self::autenticar();
                        if ($this->requisitor['admin'] == true) {
                            $usuarios = $this->listar();
                            $this->respostaSucesso("Listando todos Usuários do banco de dados",$usuarios,$this->requisitor);
                        } else {
                            throw new Exception("Você precisa ser administrador para ter acesso aos dados de todos os usuários");
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(file_get_contents("php://input"));
                    // /api/users/
                    if (count($this->url) == 2) {
                        $this->atualizar($info);
                        $this->respostaSucesso("Dados de Usuário alterados com sucesso",null,$this->requisitor);
                    }
                    break;
                case 'DELETE':
                    // /api/users
                    if (count($this->url) == 2) {
                        $this->excluir();
                        $this->respostaSucesso("Usuario Excluido com sucesso",null,$this->requisitor);
                    }
                    // /api/users/deslogar
                    elseif (count($this->url) == 3 && $this->url[2] == 'deslogar') {
                        $this->deslogar();
                        $this->respostaSucesso("Deslogado com sucesso!",null,$this->requisitor);
                    }
                    break;
            }
        }
    }

    protected function cadastrar($info)
    {
        if ($this->validaRequisicao($info)) {
            Validacao::validar('Senha',$info->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
            if(!$this->DAO->listarPorLogin($info->login)){
                // Verifica se é um cadastro de administrador e valida a senha de administrador
                $admin = 0;
                if (isset($info->senhaAdmin)) {
                    if (password_verify($info->senhaAdmin,'$2y$12$N82ObBFr3YTAgMqEck5arOTgpunRBKuUxYLK4w7x0RY35Ariwjg.O')){
                        $admin = 1;
                    } else {
                        throw new Exception("Senha para ser administrador errada");
                    }
                }
                // Encripta a senha e cria objeto validando
                $hash = password_hash($info->senha,PASSWORD_BCRYPT);
                $usuario = new UsuarioModel($info->nomeCompleto,$info->login,$hash,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao,$info->valorHora,null,$admin,null);
                // verifica se uma foto foi mandada, caso sim, o arquivo é colocado no servidor
                if (isset($_FILES['foto'])) {
                    $caminhoFoto = $this->salvarFoto($usuario->getLogin());
                } else {
                    $caminhoFoto = $_SERVER['DOCUMENT_ROOT']."/assets/files/default/perfil.png";
                }
                // Indica o caminho da foto no servidor para o objeto e cadastra esse objeto no banco de dados
                $usuario->setFoto($caminhoFoto);
                $this->DAO->cadastrar($usuario);
            }else {
                throw new Exception("Nome de Usuário já registrado");
            }
        } else {
            throw new Exception("Parametros faltando ou requisição mal estrurada");
        }
    }

    private function salvarFoto($usuario)
    {
        $foto = $_FILES['foto'];
        if ($foto['type'] == "image/png" || $foto['type'] == "image/jpeg" ) {
            $extensao = substr($foto['name'], strrpos($foto['name'], '.') + 1);

            if (move_uploaded_file($foto['tmp_name'],$caminhoFoto)) {
                return $caminhoFoto;
            } else {
                throw new Exception("Erro durante upload de arquivo");
            }
        } else {
            throw new Exception("Apenas arquivos de imagem são suportados");
        }
    }

    private function salvarfoto2($usuario,$base64)
    {
        $partes = explode(',',$base64);
        if (count($partes) == 2) {
            if (($partes[0] == "data:image/png;base64" || $partes[0] == "data:image/jpg;base64") && (base64_encode(base64_decode($partes[1], true)) === $partes[1])) {
                $extensao = array();
                preg_match('/\/(.*?);/', $partes[0], $extensao);
                $extensao = $extensao[1];
                $pastaUsuario = $_SERVER['DOCUMENT_ROOT']."/assets/files/".$usuario;
                $arquivoFoto = $pastaUsuario."/perfil.".$extensao;
                if (!is_dir($pastaUsuario)) {
                    mkdir($pastaUsuario);
                }
                if (is_file($arquivoFoto)) {
                    unlink($arquivoFoto);
                }
                $arquivoFoto = fopen($arquivoFoto,"wb");
                fwrite($arquivoFoto,base64_decode($partes[1]));
                fclose($arquivoFoto);
            } else {
                throw new Exception("Imagem inválida");
            }
        } else {
            throw new Exception("Imagem inválida");
        }

    }

    protected function excluir(){
        $this->requisitor = self::autenticar();
        $projetoControl = new ProjetoControl(null);
        $projetos = $projetoControl->listarPorIdUsuario($this->requisitor['id']);
        if ($projetos != false) {
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId(),$this->requisitor['id'])) {
                    throw new Exception("Você não pode excluir sua conta sendo dono de um projeto.Exclua seus projetos ou transfira o dominio para outro funcionário");
                }
            }
        }
        $this->DAO->excluir($this->requisitor['id']);
    }

    public function listar() {
        $usuarios = $this->DAO->listar();
        return $usuarios;
    }

    protected function atualizar($dados) {
        $this->requisitor = self::autenticar();
        //if (!isset($dados->nomeCompleto) || !isset($dados->login) ||  !isset($dados->dtNasc) || !isset($dados->cpf) || !isset($dados->rg) || !isset($dados->dtEmissao) || !isset($dados->email) || !isset($dados->atuacao) || !isset($dados->formacao) || !isset($dados->valorHora)) {
            $usuario = new UsuarioModel($dados->nomeCompleto,$dados->login,null,$dados->dtNasc,$dados->cpf,$dados->rg,$dados->dtEmissao,$dados->email,$dados->atuacao,$dados->formacao,$dados->valorHora,null,$this->requisitor['admin'],$this->requisitor['id']);
            if (isset($dados->foto)) {
                $caminhoFoto = $this->salvarFoto2($this->requisitor['login'],$dados->foto);
            } else {
                $caminhoFoto = $this->requisitor['foto'];
            }
            $usuario->setFoto($caminhoFoto);
            //var_dump($usuario);
            $this->DAO->alterar($usuario);

    }

    public function listarPorId($id){
        $this->requisitor = self::autenticar();
        $usuario = $this->DAO->listarPorId($id);
        return $usuario;
    }

    public function logar($info)
    {
        if ($info->login != "" && $info->senha != "") {
            $login = $info->login;
            $senha = $info->senha;
            if ($usuario = $this->DAO->listarPorLogin($login)) {
                if( password_verify($senha,$usuario->getSenha())){
                    $token = $this->criaToken($usuario);
                    $this->DAO->setToken($token,$usuario->getId());
                    header("Set-Cookie: token={$token}");
                    return $token;
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

    private function criaToken(UsuarioModel $usuario) {
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
                "foto" => $usuario->getFoto(),
                "admin" => $usuario->getAdmin()
            ));

        $token = JWT::encode($token, $secret_key);
        return $token;
    }

    public function deslogar() {
        $this->requisitor= self::autenticar();
        $this->DAO->deslogar($this->requisitor['id']);
    }

    public static function autenticar()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $token =  explode(' ',$_SERVER['HTTP_AUTHORIZATION']);
            if (count($token) == 2) {
                $token = $token[1];
                $decoded = JWT::decode($token,'SUPERSENHA123',array('HS256'));
                $usuarioDAO = new UsuarioDao();
                $tokenValido = $usuarioDAO->getTokenPorId($decoded->data->id);
                    if ($tokenValido == $token) {
                        $userInfo = ["id" => $decoded->data->id, "login" => $decoded->data->login,"foto" => $decoded->data->foto,"admin" => $decoded->data->admin];
                        return $userInfo;
                    } else {
                        throw new Exception("Usuário deslogado");
                    }
            } else
                throw new SignatureInvalidException();
        } else
            throw new SignatureInvalidException();
    }

    public function validaRequisicao($dados)
    {
        if (!isset($dados->nomeCompleto) || !isset($dados->login) || !isset($dados->senha) || !isset($dados->dtNasc) ||
            !isset($dados->cpf) || !isset($dados->rg) || !isset($dados->dtEmissao) || !isset($dados->email) || !isset($dados->atuacao) ||
            !isset($dados->formacao) || !isset($dados->valorHora)) {
            return false;
        } else {
            return true;
        }
    }
}