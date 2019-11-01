<?php


namespace Lasse\LPM\Services;


use DateTime;
use DateTimeZone;
use Lasse\LPM\Model\UsuarioModel;
use SplFileObject;

class Logger
{
    private $arquivoLog;
    private $data;

    public function __construct()
    {
        $this->data = new DateTime();
        $timezone = new DateTimeZone("America/Sao_Paulo");
        $this->data->setTimezone($timezone);
        $this->arquivoLog = new SplFileObject($_SERVER['DOCUMENT_ROOT']."/assets/log.txt","a");
    }
    
    public function logEntrada(UsuarioModel $usuario)
    {
        $this->arquivoLog->fwrite("[{$this->data->format("d/m/Y - H:i:s")}] Usuário {$usuario->getLogin()} logou no sistema através de {$_SERVER['HTTP_USER_AGENT']}\n");
    }

    public function logSaida(string $login)
    {
        $this->arquivoLog->fwrite("[{$this->data->format("d/m/Y - H:i:s")}] Usuário {$login} logou no sistema através de {$_SERVER['HTTP_USER_AGENT']}\n");
    }

    public function logErro(string $msgErro)
    {
        if (isset($_SESSION) && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['login'])) {
            $usuario = $_SESSION['usuario']['login'];
        } else {
            $usuario = "desconhecido";
        }
        $this->arquivoLog->fwrite("[{$this->data->format("d/m/Y - H:i:s")}] Erro ocorrido com usuário {$usuario}: {$msgErro}\n");
    }

    public function __destruct()
    {
        $this->arquivoLog = null;
    }

}

