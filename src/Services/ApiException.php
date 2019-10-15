<?php


namespace Lasse\LPM\Services;

use Exception;
use Throwable;

class ApiException extends Exception
{
    private $requisitor;

    public function __construct($message = "", $code = 0, $requisitor = null, Throwable $previous = null)
    {
        $this->requisitor = $requisitor;
        parent::__construct($message, $code, $previous);
    }
}
