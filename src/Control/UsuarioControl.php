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
                    $info = json_decode(@file_get_contents("php://input"));
                    // /api/users/login
                    if (isset($this->url[2]) && $this->url[2] == 'login' && count($this->url) == 3) {
                        if (isset($info->login) && isset($info->senha)) {
                            $token = $this->logar($info);
                            $this->respostaSucesso("Logado com Sucesso",$token,$this->requisitor);
                        } else {
                            throw new Exception("Parametros insuficientes ou mal estruturados");
                        }
                    } // /api/users
                    elseif (count($this->url) == 2) {
                        $this->cadastrar($info);
                        $this->respostaSucesso("Usuario Registrado com Sucesso!",null);
                    }
                    break;
                case 'GET':
                    // /api/users/{idUsuario}
                    if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                        $this->requisitor = self::autenticar();
                        if ($this->requisitor['id'] == $this->url[2] || $this->requisitor['admin'] == "1") {
                            $usuario = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando Usuário.",$usuario,$this->requisitor);
                        } else {
                            throw new Exception("Você não tem acesso as informações desse usuário");
                        }

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
                    // /api/users/naoProjeto/{idProjeto}
                    elseif (count($this->url) == 4 && $this->url[2] == "naoProjeto" && $this->url[3] == (int)$this->url[3]) {
                        $this->requisitor = self::autenticar();
                        $usuarios = $this->listarUsuariosForaProjeto($this->url[3]);
                        if ($usuarios) {
                            $this->respostaSucesso("Listando Usuarios",$usuarios,$this->requisitor);
                        } else {
                            $this->respostaSucesso("Todos usuários do sistemas estão no projeto",null,$this->requisitor);
                        }
                    }
                    break;
                case 'PUT':
                    $info = json_decode(file_get_contents("php://input"));
                    // /api/users/
                    if (count($this->url) == 2) {
                        $this->atualizar($info);
                        $this->respostaSucesso("Dados de Usuário alterados com sucesso, logue novamente para atualizar completamente",null,$this->requisitor);
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
        if ($this->validaRequisicao($info,'cadastro')) {
            Validacao::validar('Senha',$info->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
            if(!$this->DAO->listarPorLogin($info->login)){
                // Verifica se é um cadastro de administrador e valida a senha de administrador
                $admin = 0;
                $caminhoPadrao = "/assets/files/default/perfil.png";
                if (isset($info->senhaAdmin)) {
                    if (password_verify($info->senhaAdmin,'$2y$12$N82ObBFr3YTAgMqEck5arOTgpunRBKuUxYLK4w7x0RY35Ariwjg.O')){
                        $admin = 1;
                    } else {
                        throw new Exception("Senha para ser administrador errada");
                    }
                }
                // Encripta a senha e cria objeto validando
                $hash = password_hash($info->senha,PASSWORD_BCRYPT);
                $usuario = new UsuarioModel($info->nomeCompleto,$info->login,$hash,$info->dtNasc,$info->cpf,$info->rg,$info->dtEmissao,$info->email,$info->atuacao,$info->formacao, $info->valorHora,$caminhoPadrao,$admin,null);
                $this->DAO->cadastrar($usuario);
                // verifica se uma foto foi mandada, caso sim, o arquivo é colocado no servidor
                if (isset($info->foto)) {
                    $usuario->setId($this->DAO->pdo->lastInsertId());
                    $this->salvarFoto($usuario->getId(),$info->foto);
                    $this->DAO->alterar($usuario);
                }
            }else {
                throw new Exception("Nome de Usuário já registrado");
            }
        } else {
            throw new Exception("Parametros faltando ou requisição mal estrurada");
        }
    }

    protected function excluir(){
        $this->requisitor = self::autenticar();
        $projetoControl = new ProjetoControl(null);
        $projetos = $projetoControl->listarPorIdUsuario($this->requisitor['id']);
        if ($projetos != false) {
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId(),$this->requisitor['id'])) {
                    throw new Exception("Você não pode excluir sua conta sendo dono de um projeto.
                    Exclua seus projetos ou transfira o dominio para outro funcionário");
                }
            }
        }
        $this->DAO->excluir($this->requisitor['id']);
        $this->deslogar();
    }

    public function listar()
    {
        $usuarios = $this->DAO->listar();
        return $usuarios;
    }

    public function listarPorId($id){
        $usuario = $this->DAO->listarPorId($id);
        if ($usuario != false ) {
            return $usuario;
        } else {
            throw new Exception("Usuário não encontrado no sistema");
        }
    }

    public function listarUsuariosForaProjeto($idProjeto){
        $usuarios = $this->DAO->listarUsuariosForaProjeto($idProjeto);
        return $usuarios;
    }

    protected function atualizar($dados) {
        $this->requisitor = self::autenticar();
        $usuario = $this->listarPorId($this->requisitor['id']);
        if ($this->validaRequisicao($dados,'atualizacao')) {
            if (!$this->DAO->listarPorLogin($dados->login) || strcasecmp($usuario->getLogin(), $dados->login) == 0 ){
                $usuario = new UsuarioModel($dados->nomeCompleto, $dados->login, null, $dados->dtNasc, $dados->cpf, $dados->rg, $dados->dtEmissao, $dados->email, $dados->atuacao, $dados->formacao, $dados->valorHora, $this->requisitor['foto'], $this->requisitor['admin'], $this->requisitor['id']);
                if (isset($dados->foto)) {
                    $caminhoFoto = $this->salvarFoto($this->requisitor['id'], $dados->foto);
                    $usuario->setFoto($caminhoFoto);
                }
                $this->requisitor['login'] = $usuario->getLogin();
                $this->DAO->alterar($usuario);
            } else {
                throw new Exception("Nome de Usuário já utilizado");
            }
        } else {
            throw new Exception("Parametros invalidos ou mal estruturados");
        }
    }

    public function logar($info)
    {
        if ($info->login != "" && $info->senha != "") {
            $login = $info->login;
            $senha = $info->senha;
            if ($usuario = $this->DAO->listarPorLogin($login)) {
                if( password_verify($senha,$usuario->getSenha())){
                    $token = $this->criaToken($usuario);
                    header("Set-Cookie: token={$token}; Domain=localhost; Path=/");
                    $this->DAO->setToken($token,$usuario->getId());
                    $decoded = JWT::decode($token,'SUPERSENHA123',array('HS256'));
                    $usuario = $this->DAO->listarPorId($decoded->data->id);
                    $this->requisitor = ["id" => $usuario->getId(), "login" => $usuario->getLogin(),"foto" => $usuario->getFoto(),"admin" => $usuario->getAdmin()];
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
                    $usuario = $usuarioDAO->listarPorId($decoded->data->id);
                    $userInfo = ["id" => $usuario->getId(), "login" => $usuario->getLogin(),"foto" => $usuario->getFoto(),"admin" => $usuario->getAdmin()];
                    return $userInfo;
                } else {
                    throw new Exception("Usuário deslogado");
                }
            } else
                throw new Exception("Você precisa estar logado");
        } else
            throw new Exception("Você precisa estar logado");
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
                "id" => $usuario->getId()
            ));

        $token = JWT::encode($token, $secret_key);
        return $token;
    }

    public function validaRequisicao($dados,$requisicao)
    {
        if ($requisicao == 'cadastro') {
            if (!isset($dados->nomeCompleto) || !isset($dados->login) || !isset($dados->senha) || !isset($dados->dtNasc) ||
                !isset($dados->cpf) || !isset($dados->rg) || !isset($dados->dtEmissao) || !isset($dados->email) ||
                !isset($dados->atuacao) || !isset($dados->formacao) || !isset($dados->valorHora)) {
                return false;
            }
        }
        if ($requisicao == 'atualizacao') {
            if (!isset($dados->nomeCompleto) || !isset($dados->login) ||  !isset($dados->dtNasc) || !isset($dados->cpf)
                || !isset($dados->rg) || !isset($dados->dtEmissao) || !isset($dados->email) || !isset($dados->atuacao)
                || !isset($dados->formacao) || !isset($dados->valorHora)) {
                return false;
            }
        }
        return true;
    }

    private function salvarfoto($idUsuario,$imgBase64)
    {
        $partes = explode(',',$imgBase64);
        if (count($partes) == 2) {
            if (($partes[0] == "data:image/png;base64" || $partes[0] == "data:image/jpeg;base64" || $partes[0] == "data:image/jpg;base64") && (base64_encode(base64_decode($partes[1], true)) === $partes[1])) {
                $imgInfo = $partes[0];
                $img = $partes[1];
                preg_match('/\/(.*?);/', $imgInfo, $extensao);
                $extensao = $extensao[1];
                $pastaUsuario = "assets/files/".$idUsuario;
                $caminhoFoto = $pastaUsuario."/perfil.".$extensao;
                if (!is_dir($pastaUsuario)) {
                    mkdir($pastaUsuario);
                }
                if (is_file($caminhoFoto)) {
                    unlink($caminhoFoto);
                }
                $arquivoFoto = fopen($caminhoFoto,"wb");
                fwrite($arquivoFoto,base64_decode($partes[1]));
                fclose($arquivoFoto);
                return $caminhoFoto;
            } else {
                throw new Exception("Imagem inválida");
            }
        } else {
            throw new Exception("Imagem inválida");
        }
    }
}
