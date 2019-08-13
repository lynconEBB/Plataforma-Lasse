<?php

use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

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

function handleExceptionTypes(Exception $exception) {
    $mensagem = "";
    $exceptionClass = get_class($exception);

    switch ($exceptionClass) {
        case SignatureInvalidException::class:
            http_response_code(401);
            $mensagem = "Token de Authenticação invalido ou não encontrado";
            break;
        case Exception::class:
            $mensagem = $exception->getMessage();
            break;
        case PDOException::class:
            http_response_code(500);
            $mensagem = "Erro durante transação com Banco de dados";
            break;
        case ExpiredException::class:
            http_response_code(401);
            $mensagem = "Tempo de sessão expirado.";
            if(isset($_COOKIE['token'])) {
                unset($_COOKIE['token']);
            }
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
    echo json_encode($response);
}

set_error_handler('errorToException');
set_exception_handler('handleExceptionTypes');