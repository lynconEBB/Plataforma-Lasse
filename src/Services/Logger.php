<?php


namespace Lasse\LPM\Services;


use DateTime;
use Lasse\LPM\Model\UsuarioModel;
use SplFileObject;

class Logger
{
    private $arquivoLog;

    public function __construct()
    {
        $this->arquivoLog = new SplFileObject($_SERVER['DOCUMENT_ROOT']."/assets/log.txt","a");
    }
    
    public function logEntrada(UsuarioModel $usuario):void
    {
        $dataAgora = new DateTime();
        $this->arquivoLog->fwrite("[{$dataAgora->format("d/m/Y - h:i:s")}]: Usuário {$usuario->getLogin()} logou no sistema através de {$_SERVER['HTTP_USER_AGENT']}\n");
    }

    public function logSaida()
    {

    }


    public function __destruct()
    {
        $this->arquivoLog = null;
    }

}

