<?php

use Lasse\LPM\Services\ApiException;


class ErroException extends Exception
{
    public function __construct( $code, $message, $file, $line)
    {
        parent::__construct( $message, $code );
        $this->file = $file;
        $this->line = $line;
    }
}

function errorToException( $code, $message, $file, $line )
{
    throw new ErroException( $code, $message, $file, $line );
}

function handleExceptionTypes($exception) {
    $exceptionClass = get_class($exception);

    switch ($exceptionClass) {
        case ApiException::class:
            http_response_code($exception->getCode());
            $mensagem = $exception->getMessage();
            break;
        case InvalidArgumentException::class:
            http_response_code(400);
            $mensagem = $exception->getMessage();
            break;
        case PDOException::class:
            http_response_code(500);
            $mensagem = "Erro durante transação com Banco de dados";
            break;
        case ErroException::class:
        default:
            http_response_code(500);
            $mensagem = "Erro Interno Inesperado";
            break;
    }

    $response = [
        "status" => "erro",
        "mensagem" => $mensagem,
        "dados" => [
            "codigo" => $exception->getCode(),
            "mensagem" => $exception->getMessage(),
            "arquivo" => $exception->getFile(),
            "linha" => $exception->getLine(),
        ]
    ];

    if (isset($_SESSION) && isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
        $response['requistor'] = $_SESSION['usuario'];
    }

    echo json_encode($response);
}

set_error_handler('errorToException');
set_exception_handler('handleExceptionTypes');
