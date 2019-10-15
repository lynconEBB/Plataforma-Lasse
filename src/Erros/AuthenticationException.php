<?php


namespace Lasse\LPM\Erros;


use Exception;
use Throwable;

class AuthenticationException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
