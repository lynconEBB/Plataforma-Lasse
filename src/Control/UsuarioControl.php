<?php

namespace Lasse\LPM\Control;


use DateInterval;
use DatePeriod;
use DateTime;
use InvalidArgumentException;
use Lasse\LPM\Dao\AtividadeDao;
use Lasse\LPM\Dao\UsuarioDao;
use Lasse\LPM\Erros\AuthenticationException;
use Lasse\LPM\Erros\MailException;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Model\UsuarioModel;
use Lasse\LPM\Services\Logger;
use Lasse\LPM\Services\Validacao;
use PHPMailer\PHPMailer\PHPMailer;
use UnexpectedValueException;


class UsuarioControl extends CrudControl {

    public function __construct($url)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->DAO = new UsuarioDao();
        parent::__construct($url);
    }

    public function processaRequisicao()
    {
        if ($this->url != null) {
            $requisicaoEncontrada = false;
            switch ($this->metodo) {
                case 'POST':
                    $body = json_decode(@file_get_contents("php://input"));
                    // /api/users/login
                    if (isset($this->url[2]) && $this->url[2] == 'login' && count($this->url) == 3) {
                        $requisicaoEncontrada = true;
                        if (isset($body->login) && isset($body->senha)) {
                            $this->logar($body);
                            $this->respostaSucesso("Logado com Sucesso",null,$_SESSION['usuario']);
                        } else {
                            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
                        }
                    } // /api/users
                    elseif (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->cadastrar($body);
                        $this->respostaSucesso("Registrado com sucesso, para ter acesso ao sistema ative sua conta através do link enviado ao seu e-mail!",null);
                    }
                    // /api/users/geraRecuperacao/
                    elseif (count($this->url) == 3 && $this->url[2] == "geraRecuperacao") {
                        $requisicaoEncontrada = true;
                        if (isset($body->email)) {
                            $this->enviaEmailRecuperacao($body->email);
                            $this->respostaSucesso("Um link de recuperação de senha foi enviado para seu email",null,null);
                        } else {
                            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
                        }
                    }
                    // /api/users/tempoGastoDiario/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[2] == "tempoGastoDiario" && $this->url[3] == (int)$this->url[3]) {
                        $requisicaoEncontrada = true;
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1" || $_SESSION['usuario']['id'] == $this->url[3]) {
                            $dados = $this->dadosTempoDiario($this->url[3],$body);
                            if ($dados != false) {
                                $this->respostaSucesso("Listando dados para construção do gráfico",$dados,$_SESSION['usuario']);
                            } else {
                                $this->respostaSucesso("Nenhum dado encontrado",null,$_SESSION['usuario']);
                                http_response_code(202);
                            }
                        } else {
                            throw new PermissionException("Você não possui acesso aos gráficos deste usuário","Gerar Gráfico de outro usuário");
                        }
                    }
                    break;
                case 'GET':
                    // /api/users/{idUsuario}
                    if (isset($this->url[2]) && is_numeric($this->url[2]) && count($this->url) == 3) {
                        $requisicaoEncontrada = true;
                        self::autenticar();
                        if ($_SESSION['usuario']['id'] == $this->url[2] || $_SESSION['usuario']['admin'] == "1") {
                            $usuario = $this->listarPorId($this->url[2]);
                            $this->respostaSucesso("Listando Usuário: ".$usuario->getLogin(),$usuario,$_SESSION['usuario']);
                        } else {
                            throw new PermissionException("Você não tem acesso as informações desse usuário","Acessar informações não pertencentes ao mesmo");
                        }
                    }
                    // /api/users
                    elseif (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1") {
                            $usuarios = $this->listar();
                            $this->respostaSucesso("Listando todos Usuários do banco de dados",$usuarios,$_SESSION['usuario']);
                        } else {
                            throw new PermissionException("Você precisa ser administrador para ter acesso aos dados de todos os usuários","Acessar informações de todos os usuários");
                        }
                    }
                    // /api/users/desativados
                    elseif (count($this->url) == 3 && $this->url[2] == "desativados") {
                        $requisicaoEncontrada = true;
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1") {
                            $usuarios = $this->listarDesativados();
                            if ($usuarios != false) {
                                $this->respostaSucesso("Listando todos usuários desativados do banco de dados",$usuarios,$_SESSION['usuario']);
                            } else {
                                $this->respostaSucesso("Nenhum usuário desativado encontrado",null,$_SESSION['usuario']);
                                http_response_code(202);
                            }
                        } else {
                            throw new PermissionException("Você precisa ser administrador para ter acesso aos dados de todos os usuários","Acessar informações de todos os usuários");
                        }
                    }
                    // /api/users/naoProjeto/{idProjeto}
                    elseif (count($this->url) == 4 && $this->url[2] == "naoProjeto" && $this->url[3] == (int)$this->url[3]) {
                        $requisicaoEncontrada = true;
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
                        $requisicaoEncontrada = true;
                        $this->atualizar($body);
                        $this->respostaSucesso("Dados de Usuário alterados com sucesso, logue novamente para atualizar completamente",null,$_SESSION['usuario']);
                    }
                    // /api/users/reativar/{idUsuario}
                    elseif (count($this->url) == 4 && $this->url[2] == "reativar" && $this->url[3] == (int)$this->url[3])  {
                        $requisicaoEncontrada = true;
                        self::autenticar();
                        if ($_SESSION['usuario']['admin'] == "1") {
                            $this->reativar($this->url[3]);
                            $this->respostaSucesso("Usuário reativado com sucesso",null,$_SESSION['usuario']);
                        } else {
                            throw new PermissionException("Você precisa ser administrador para ter acesso aos dados de todos os usuários","Reativar um usuário");
                        }
                    }
                    // /api/users/confirmar/{token}
                    elseif (count($this->url) == 4 && $this->url[2] == "confirmar")  {
                        $requisicaoEncontrada = true;
                        $this->confirmarConta($this->url[3]);
                        $this->respostaSucesso("Conta de usuário confirmada com sucesso!",null,null);
                    }
                    // /api/users/alterarSenha
                    elseif (count($this->url) == 3 && $this->url[2] == "alterarSenha") {
                        $requisicaoEncontrada = true;
                        if (isset($body->novaSenha) && isset($body->token)) {
                            $this->alterarSenha($body);
                            $this->respostaSucesso("Senha Alterada com sucesso",null,null);
                        } else {
                            throw new UnexpectedValueException("Parametros insuficientes ou mal estruturados");
                        }
                    }
                    break;
                case 'DELETE':
                    // /api/users
                    if (count($this->url) == 2) {
                        $requisicaoEncontrada = true;
                        $this->excluir();
                        $this->respostaSucesso("Usuario Excluido com sucesso",null,$_SESSION['usuario']);
                    }
                    // /api/users/deslogar
                    elseif (count($this->url) == 3 && $this->url[2] == 'deslogar') {
                        $requisicaoEncontrada = true;
                        $this->deslogar();
                        $this->respostaSucesso("Deslogado com sucesso!",null,$_SESSION['usuario']);
                    }
                    break;
            }
            if (!$requisicaoEncontrada) {
                throw new NotFoundException("URL não encontrada");
            }
        }
    }

    public function enviaEmailConfirmacao(UsuarioModel $usuario){
        $token = uniqid($usuario->getId()."-");
        $this->DAO->setTokenConfirmacao($token,$usuario->getId());
        $mail = $this->presetEmail();
        $mail->addAddress($usuario->getEmail());
        $mail->Subject = utf8_decode('Confirmação de cadastro');
        $mail->Body = "<h1>Clique no link abaixo para Confirmar seu cadastro</h1>
                       <a href='{$_SERVER['HTTP_HOST']}/confirmar?{$token}'>Confirmar cadastro</a>";
        if($mail->Send()):
            return;
        else:
            throw new MailException("Erro durante envio do E-mail");
        endif;
    }

    public function enviaEmailRecuperacao($email) {
        if ($usuario = $this->DAO->listarPorEmail($email)) {
            $token = uniqid($usuario->getId()."-");
            $this->DAO->setTokenRecuperacao($token,$usuario->getId());
            $mail = $this->presetEmail();
            $mail->addAddress($email);
            $mail->Subject = utf8_decode('Alteração de Senha');
            $mail->Body = "<meta charset='utf-8'>
                <h1>Clique no botão abaixo para alterar sua senha</h1>
                <a href='http://localhost/senhaAlterar?{$token}'>Alterar Senha</a>";
            if($mail->Send()):
                return;
            else:
                throw new MailException("Erro durante envio do E-mail");
            endif;
        } else {
            throw new NotFoundException("E-mail não cadastrado no sistema.");
        }
    }

    public function presetEmail() {
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
        return $mail;
    }

    public function confirmarConta($token){
        $partes = explode("-",$token);
        if (count($partes) == 2) {
            $idUsuario = $partes[0];
            $usuario = $this->listarPorId($idUsuario);
            $tokenReal = $this->DAO->getTokenConfirmacao($idUsuario);
            if ($tokenReal === $token && $tokenReal != null) {
               $this->reativar($idUsuario);
               $this->DAO->setTokenConfirmacao(null,$idUsuario);
            } else {
                throw new PermissionException("Codigo de confirmação para {$usuario->getLogin()} incorreto","Redefinir senha de acesso de {$usuario->getLogin()}");
            }
        } else {
            throw new UnexpectedValueException("Codigo de confirmação inválido");
        }
    }

    public function alterarSenha($body)
    {
        $partes = explode("-",$body->token);
        if (count($partes) == 2) {
            $idUsuario = $partes[0];
            $usuario = $this->listarPorId($idUsuario);
            $tokenReal = $this->DAO->getTokenRecuperacao($idUsuario);
            if ($tokenReal === $body->token && $tokenReal != null) {
                Validacao::validar("Nova senha",$body->novaSenha,'semEspaco','obrigatorio','texto',['minimo',6]);
                $hash = password_hash($body->novaSenha,PASSWORD_BCRYPT);
                $this->DAO->alterarSenha($hash,$idUsuario);
                $this->DAO->setTokenRecuperacao(null,$idUsuario);
            } else {
                throw new PermissionException("Codigo de recuperação para {$usuario->getLogin()} incorreto","Redefinir senha de acesso de {$usuario->getLogin()}");
            }
        } else {
            throw new UnexpectedValueException("Codigo de recuperação inválido");
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
                                throw new InvalidArgumentException("Senha de administrador errada");
                            }
                        }
                        // Encripta a senha e cria objeto validando
                        $hash = password_hash($body->senha,PASSWORD_BCRYPT);
                        $usuario = new UsuarioModel($body->nomeCompleto,$body->login,$hash,$body->dtNasc,$body->cpf,$body->rg,$body->dtEmissao,$body->email,$body->atuacao,$body->formacao, $body->valorHora,$caminhoPadrao,$admin,null);
                        $this->DAO->cadastrar($usuario);
                        $usuario->setId($this->DAO->pdo->lastInsertId());
                        // verifica se uma foto foi mandada, caso sim, o arquivo é colocado no servidor
                        if (isset($body->foto)) {
                            $caminhoFoto = $this->salvarFoto($usuario->getId(),$body->foto);
                            $usuario->setFoto($caminhoFoto);
                            $this->DAO->alterar($usuario);
                        }
                        $this->enviaEmailConfirmacao($usuario);
                    } else
                        throw new InvalidArgumentException("CPF já registrado");
                }else
                    throw new InvalidArgumentException("Nome de Usuário já registrado");
            } else
                throw new InvalidArgumentException("E-mail já registrado");
        } else {
            throw new UnexpectedValueException("Parâmetros faltando ou requisição mal estrurada");
        }
    }

    protected function excluir()
    {
        self::autenticar();
        $projetoControl = new ProjetoControl(null);
        $projetos = $projetoControl->listarPorIdUsuario($_SESSION['usuario']['id']);
        if ($projetos != false) {
            foreach ($projetos as $projeto){
                if ($projetoControl->verificaDono($projeto->getId(),$_SESSION['usuario']['id'])) {
                    throw new PermissionException("Você não pode excluir sua conta sendo dono de um projeto.Exclua seus projetos ou transfira o dominio para outro funcionário","Excluir perfil de usuário");
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

    public function listarDesativados()
    {
        $usuarios = $this->DAO->listarDesativados();
        return $usuarios;
    }

    public function listarPorId($id)
    {
        $usuario = $this->DAO->listarPorId($id);
        if ($usuario != false ) {
            return $usuario;
        } else {
            throw new NotFoundException("Usuário não encontrado no sistema");
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
                if (!$this->DAO->listarPorCpf($dados->cpf) || $dados->cpf == $usuario->getCpf()) {
                    $usuario = new UsuarioModel($dados->nomeCompleto, $dados->login, null, $dados->dtNasc, $dados->cpf, $dados->rg, $dados->dtEmissao, $dados->email, $dados->atuacao, $dados->formacao, $dados->valorHora, $_SESSION['usuario']['foto'], $_SESSION['usuario']['admin'], $_SESSION['usuario']['id']);
                    if (isset($dados->foto)) {
                        $caminhoFoto = $this->salvarFoto($_SESSION['usuario']['id'], $dados->foto);
                        $usuario->setFoto($caminhoFoto);
                        $_SESSION['usuario']['foto'] = $usuario->getFoto();
                    }
                    $_SESSION['usuario']['login'] = $usuario->getLogin();
                    $this->DAO->alterar($usuario);
                } else {
                    throw new InvalidArgumentException("CPF já Utilizado",400);
                }
            } else {
                throw new InvalidArgumentException("Nome de Usuário já utilizado");
            }
        } else {
            throw new UnexpectedValueException("Parâmetros invalidos ou mal estruturados");
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
            if ($usuario = $this->DAO->listarPorLoginAtivados($login)) {
                if (password_verify($senha,$usuario->getSenha())) {
                    $_SESSION['usuario'] = [
                        "id" => $usuario->getId(),
                        "login" => $usuario->getLogin(),
                        "admin" => $usuario->getAdmin(),
                        "foto" => $usuario->getFoto()
                    ];
                    $logger = new Logger();
                    $logger->logEntrada($usuario);
                } else {
                    throw new InvalidArgumentException("Senha errada");
                }
            } else {
                throw new InvalidArgumentException("Usuário não registrado");
            }
        } else {
            throw new InvalidArgumentException("Preencha os campos para fazer o login");
        }
    }

    public function deslogar() {
        self::autenticar();

        if ( isset( $_COOKIE[session_name()] ) ) {
            setcookie(session_name(),"",time()-3600,"/");
        }
        session_destroy();
    }

    public static function autenticar()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION) &&  isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
            return $_SESSION['usuario'];
        } else {
            throw new AuthenticationException("Logue no sistema para ter acesso");
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

                $caminhoFoto = $pastaUsuario."/perfil".time().".".$extensao;
                if (!is_dir($pastaUsuario)) {
                    mkdir($pastaUsuario);
                }
                foreach(glob($pastaUsuario."/*.jpg") as $file) {
                    unlink($file);
                }
                foreach(glob($pastaUsuario."/*.png") as $file) {
                    unlink($file);
                }
                foreach(glob($pastaUsuario."/*.jpeg") as $file) {
                    unlink($file);
                }
                $arquivoFoto = fopen($caminhoFoto,"wb");
                fwrite($arquivoFoto,base64_decode($img));
                fclose($arquivoFoto);
                return $caminhoFoto;
            } else {
                throw new InvalidArgumentException("Imagem inválida");
            }
        } else {
            throw new InvalidArgumentException("Imagem inválida");
        }
    }

    private function dadosTempoDiario($idUsuario,$body) {
        if (isset($body->mes) && isset($body->ano)) {
            if ($body->mes >= 1 && $body->mes <=12 && $body->ano >= 2005 && $body->ano <= date("Y")) {
                $primeiroDia = new DateTime("$body->ano-$body->mes-01");
                $ultimoDia = new DateTime($primeiroDia->format("Y-m-t"));
                $ultimoDia = $ultimoDia->modify("+1 day");

                $intervalo = new DateInterval("P1D");
                $periodo = new DatePeriod($primeiroDia,$intervalo,$ultimoDia);

                $atividadeDao = new AtividadeDao();
                $atividadesProjeto = $atividadeDao->listarAtividadesPeriodo($idUsuario,$primeiroDia->format("Y-m-d"));
                $projetoControl = new ProjetoControl(null);
                $projetosModel = $projetoControl->listarPorIdUsuario($idUsuario);
                $todosProjetos = [];
                foreach ($projetosModel as $projetoModel) {
                    $todosProjetos[] = $projetoModel->getNome();
                }
                $projetos = $projetoControl->DAO->listarProjetosComAtividadesDoUsuario($idUsuario);
                $todosProjetos = array_unique(array_merge($projetos,$todosProjetos));

                if ($atividadesProjeto != false) {
                    $dadosFormatados = [];
                    foreach ($todosProjetos as $projeto) {
                        foreach ($periodo as $data) {
                            $dadosFormatados[$projeto][$data->format("d/m/Y")] = 0;
                        }
                    }
                    foreach ($atividadesProjeto as $atividade) {
                        $projeto = $atividade["nome"];
                        $dia = $atividade[0];
                        $tempoGasto = $atividade["tempoGasto"];

                        $dadosFormatados[$projeto][$dia] += $tempoGasto;
                    }

                    return $dadosFormatados;
                } else {
                    return false;
                }
            } else {
                throw new InvalidArgumentException("Ano ou mês inválido");
            }
        } else {
            throw new UnexpectedValueException("Parâmentros mal estruturados ou inexistentes");
        }
    }
}
