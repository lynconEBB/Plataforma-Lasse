<?php


namespace Lasse\LPM\Erros;


use Exception;
use Throwable;

class PermissionException extends Exception
{
    private $evento;

    public function __construct(string $message,string $evento, $code = 0, Throwable $previous = null)
    {
        $this->evento = $evento;
        parent::__construct($message, $code, $previous);
    }

    public function getEvento()
    {
        return $this->evento;
    }


}
