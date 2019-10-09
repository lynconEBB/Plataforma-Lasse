<?php

namespace Lasse\LPM\Control;

use Exception;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\ApiException;
use Lasse\LPM\Services\Validacao;
use PHPMailer\PHPMailer\PHPMailer;


class UsuarioControl extends CrudControl {

    public function __construct($url)
    {
        session_start();
        $this->DAO = new UsuarioDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            switch ($this->metodo) {
                case 'POST':
                    $body = json_decode(@file_get_contents("php://input"));
                    // /api/users/login
                    if (isset($this->url[2]) && $this->url[2] == 'login' && count($this->url) == 3) {
                        if (isset($body->login) && isset($body->senha)) {
                            $this->logar($body);
                            $this->respostaSucesso("Logado com Sucesso",null,$_SESSION['usuario']);
                        } else {
                            throw new ApiException("Parametros insuficientes ou mal estruturados",400);
                        }
                    } // /api/users
                    elseif (count($this->url) == 2) {
                        $this->cadastrar($body);
                        $this->respostaSucesso("Usuario Registrado com Sucesso!",null);
                    }
                    // /api/users/geraRecuperacao/
                    elseif (count($this->url) == 3 && $this->url[2] == "geraRecuperacao") {
                        if (isset($body->email)) {
                            $this->enviaEmail($body->email);
                            $this->respostaSucesso("Um link de recuperação de senha foi enviado para seu email",null,$_SESSION['usuario']);
                        }
                    }
                    break;
                case 'GET':
                    // /api/users/{idUsuario}
                    if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                        self::autenticar();
                        if ($_SESSION['usuario']['id'] == $this->url[2] || $_SESSION['usuario']['admin'] == "1") {
                            $usuario = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando Usuário: ".$usuario->getLogin(),$usuario,$_SESSION['usuario']);
                        } else {
                            throw new ApiException("Você não tem acesso as informações desse usuário",401);
                        }

                    } // /api/users
                    elseif (count($this->url) == 2) {
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1") {
                            $usuarios = $this->listar();
                            $this->respostaSucesso("Listando todos Usuários do banco de dados",$usuarios,$_SESSION['usuario']);
                        } else {
                            throw new ApiException("Você precisa ser administrador para ter acesso aos dados de todos os usuários",401);
                        }
                    }
                    // /api/users/naoProjeto/{idProjeto}
                    elseif (count($this->url) == 4 && $this->url[2] == "naoProjeto" && $this->url[3] == (int)$this->url[3]) {
                        self::autenticar();
                        $usuarios = $this->listarUsuariosForaProjeto($this->url[3]);
                        if ($usuarios) {
                            $this->respostaSucesso("Listando Usuarios",$usuarios,$_SESSION['usuario']);
                        } else {
                            $this->respostaSucesso("Todos usuários do sistemas estão no projeto",null,$_SESSION['usuario']);
                        }
                    }
                    break;
                case 'PUT':
                    $body = json_decode(file_get_contents("php://input"));
                    // /api/users/
                    if (count($this->url) == 2) {
                        $this->atualizar($body);
                        $this->respostaSucesso("Dados de Usuário alterados com sucesso, logue novamente para atualizar completamente",null,$_SESSION['usuario']);
                    }
                    // /api/users/reativar/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[2] == "reativar" && $this->url[3] == (int)$this->url[3])  {
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1") {
                            $this->reativar($this->url[3]);
                            $this->respostaSucesso("Usuário reativado com sucesso",null,$_SESSION['usuario']);
                        } else {
                            throw new ApiException("Você precisa ser administrador para ter acesso aos dados de todos os usuários",401);
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/users
                    if (count($this->url) == 2) {
                        $this->excluir();
                        $this->respostaSucesso("Usuario Excluido com sucesso",null,$_SESSION['usuario']);
                    }
                    // /api/users/deslogar
                    elseif (count($this->url) == 3 && $this->url[2] == 'deslogar') {
                        $this->deslogar();
                        $this->respostaSucesso("Deslogado com sucesso!",null,$_SESSION['usuario']);
                    }
                    break;
            }
        }
    }

    public function enviaEmail($email) {
        if ($usuario = $this->DAO->listarPorEmail($email)) {
            $token = uniqid($usuario->getId());
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'lasseprojectmanager@gmail.com';
            $mail->Password = 'lasse123';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->IsHTML(true);
            $mail->From = 'lasseprojectmanager@gmail.com';
            $mail->FromName = 'LASSE Manager';
            $mail->addAddress($email);
            $mail->Subject = utf8_decode('Recuperação de Senha');
            $mail->Body = "<h1>Clique no botão abaixo para alterar sua senha</h1><a href='http://localhost/senhaAlterar?token={$token}'>Alterar Senha</a>";

            if($mail->Send()):
                return;
            else:
                throw new ApiException("Erro ao tentar enviar e-mail",400);
            endif;
        } else {
            throw new ApiException("E-mail não cadastrado no sistema.",400);
        }

    }

    protected function cadastrar($body)
    {
        if ($this->validaRequisicao($body,'cadastro')) {
            Validacao::validar('Senha',$body->senha,'semEspaco','obrigatorio','texto',['minimo',6]);
            if (!$this->DAO->listarPorEmail($body->email)) {
                if(!$this->DAO->listarPorLogin($body->login)){
                    if (!$this->DAO->listarPorCpf($body->cpf)) {
                        // Verifica se é um cadastro de administrador e valida a senha de administrador
                        $admin = 0;
                        $caminhoPadrao = "assets/files/default/perfil.png";
                        if (isset($body->senhaAdmin)) {
                            if (password_verify($body->senhaAdmin,'$2y$12$N82ObBFr3YTAgMqEck5arOTgpunRBKuUxYLK4w7x0RY35Ariwjg.O')){
                                $admin = 1;
                            } else {
                                throw new ApiException("Senha de administrador errada",400);
                            }
                        }
                        // Encripta a senha e cria objeto validando
                        $hash = password_hash($body->senha,PASSWORD_BCRYPT);
                        $usuario = new UsuarioModel($body->nomeCompleto,$body->login,$hash,$body->dtNasc,$body->cpf,$body->rg,$body->dtEmissao,$body->email,$body->atuacao,$body->formacao, $body->valorHora,$caminhoPadrao,$admin,null);
                        $this->DAO->cadastrar($usuario);
                        // verifica se uma foto foi mandada, caso sim, o arquivo é colocado no servidor
                        if (isset($body->foto)) {
                            $usuario->setId($this->DAO->pdo->lastInsertId());
                            $caminhoFoto = $this->salvarFoto($usuario->getId(),$body->foto);
                            $usuario->setFoto($caminhoFoto);
                            $this->DAO->alterar($usuario);
                        }
                    } else
                        throw new ApiException("CPF já registrado",400);
                }else
                    throw new ApiException("Nome de Usuário já registrado",400);
            } else
                throw new ApiException("E-mail já registrado",400);
        } else {
            throw new ApiException("Parâmetros faltando ou requisição mal estrurada",400);
        }
    }

    protected function excluir(){
        self::autenticar();
        $projetoControl = new ProjetoControl(null);
        $projetos = $projetoControl->listarPorIdUsuario($_SESSION['usuario']['id']);
        if ($projetos != false) {
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId(),$_SESSION['usuario']['id'])) {
                    throw new ApiException("Você não pode excluir sua conta sendo dono de um projeto.Exclua seus projetos ou transfira o dominio para outro funcionário",401);
                }
            }
        }
        $this->DAO->excluir($_SESSION['usuario']['id']);
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
            throw new ApiException("Usuário não encontrado no sistema",400);
        }
    }

    public function listarUsuariosForaProjeto($idProjeto){
        $usuarios = $this->DAO->listarUsuariosForaProjeto($idProjeto);
        return $usuarios;
    }

    protected function atualizar($dados) {
        self::autenticar();
        $usuario = $this->listarPorId($_SESSION['usuario']['id']);
        if ($this->validaRequisicao($dados,'atualizacao')) {
            if (!$this->DAO->listarPorLogin($dados->login) || strcasecmp($usuario->getLogin(), $dados->login) == 0 ){
                if (!$this->DAO->listarPorCpf($dados->cpf)) {
                    $usuario = new UsuarioModel($dados->nomeCompleto, $dados->login, null, $dados->dtNasc, $dados->cpf, $dados->rg, $dados->dtEmissao, $dados->email, $dados->atuacao, $dados->formacao, $dados->valorHora, $_SESSION['usuario']['foto'], $_SESSION['usuario']['admin'], $_SESSION['usuario']['id']);
                    if (isset($dados->foto)) {
                        $caminhoFoto = $this->salvarFoto($_SESSION['usuario']['id'], $dados->foto);
                        $usuario->setFoto($caminhoFoto);
                        $_SESSION['usuario']['foto'] = $usuario->getFoto();
                    }
                    $_SESSION['usuario']['login'] = $usuario->getLogin();
                    $this->DAO->alterar($usuario);
                } else {
                    throw new ApiException("CPF já Utilizado",400);
                }
            } else {
                throw new ApiException("Nome de Usuário já utilizado",400);
            }
        } else {
            throw new ApiException("Parâmetros invalidos ou mal estruturados",400);
        }
    }

    public function reativar($idUsuario) {
        $this->listarPorId($idUsuario);
        $this->DAO->reativar($idUsuario);
    }

    public function logar($body)
    {
        if ($body->login != "" && $body->senha != "") {
            $login = $body->login;
            $senha = $body->senha;
            if ($usuario = $this->DAO->listarPorLogin($login)) {
                if (password_verify($senha,$usuario->getSenha())) {
                    $_SESSION['usuario'] = [
                        "id" => $usuario->getId(),
                        "login" => $usuario->getLogin(),
                        "admin" => $usuario->getAdmin(),
                        "foto" => $usuario->getFoto()
                    ];
                } else {
                    throw new ApiException("Senha errada",400);
                }
            } else {
                throw new ApiException("Usuário não registrado",400);
            }
        } else {
            throw new ApiException("Preencha os campos para fazer o login",400);
        }
    }

    public function deslogar() {
        self::autenticar();
        session_destroy();
    }

    public static function autenticar()
    {
        if (isset($_SESSION) &&  isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
            return;   
        } else {
            throw new ApiException("Logue no sistema para ter acesso",401);
        }
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
                fwrite($arquivoFoto,base64_decode($img));
                fclose($arquivoFoto);
                return $caminhoFoto;
            } else {
                throw new ApiException("Imagem inválida",400);
            }
        } else {
            throw new ApiException("Imagem inválida",400);
        }
    }
}
