<?php

use Lasse\LPM\Erros\AuthenticationException;
use Lasse\LPM\Erros\MailException;
use Lasse\LPM\Erros\NotFoundException;
use Lasse\LPM\Erros\PermissionException;
use Lasse\LPM\Services\Logger;


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
    $mensagem = $exception->getMessage();

    switch ($exceptionClass) {
        case AuthenticationException::class:
            http_response_code(405);
            break;
        case NotFoundException::class:
            http_response_code(404);
            break;
        case MailException::class:
            http_response_code(500);
            $logger = new Logger();
            $logger->logErro($mensagem);
            break;
        case PermissionException::class:
            http_response_code(401);
            $logger = new Logger();
            $logger->logErro("Permissão negada ao tentar {$exception->getEvento()}");
            break;
        case UnexpectedValueException::class:
        case InvalidArgumentException::class:
            http_response_code(400);
            break;
        case PDOException::class:
            http_response_code(500);
            $logger = new Logger();
            $mensagem = "Erro durante transação com Banco de dados";
            $logger->logErro($mensagem.": ".$exception->getMessage());
            break;
        default:
            http_response_code(500);
            $logger = new Logger();
            $mensagem = "Erro Interno Inesperado";
            $logger->logErro($mensagem.": ".$exception->getMessage());
            break;
    }

    header("Content-type: application/json; charset=utf-8");

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
