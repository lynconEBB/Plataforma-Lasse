<?php


namespace Lasse\LPM\Services;


use DateTime;
use Lasse\LPM\Model\UsuarioModel;
use SplFileObject;

class Logger
{
    private $arquivoLog;
    private $data;

    public function __construct()
    {
        $this->data = new DateTime();
        $this->arquivoLog = new SplFileObject($_SERVER['DOCUMENT_ROOT']."/assets/log.txt","a");
    }
    
    public function logEntrada(UsuarioModel $usuario):void
    {
        $this->arquivoLog->fwrite("[{$this->data->format("d/m/Y - h:i:s")}]: Usuário {$usuario->getLogin()} logou no sistema através de {$_SERVER['HTTP_USER_AGENT']}\n");
    }

    public function logSaida(string $login)
    {
        $this->arquivoLog->fwrite("[{$this->data->format("d/m/Y - h:i:s")}]: Usuário {$login} logou no sistema através de {$_SERVER['HTTP_USER_AGENT']}\n");
    }

    public function __destruct()
    {
        $this->arquivoLog = null;
    }

}

