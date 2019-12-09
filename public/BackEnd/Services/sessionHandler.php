<?php


class FileSessionHandler extends SessionHandler
{
    function destroy($id)
    {
        $string = $this->read($id);

        preg_match('/s:5:"login";s:\d+:("\w+")/',$string,$matches);
        if (count($matches) == 2) {
            $login = $matches[1];
            $login = str_replace('"',"",$login);
            $arquivoLog =new SplFileObject($_SERVER['DOCUMENT_ROOT']."/assets/log.txt","a");
            $data = new DateTime();
            $arquivoLog->fwrite("[{$data->format("d/m/Y - h:i:s")}]: Usuário {$login} deslogou no sistema em {$_SERVER['HTTP_USER_AGENT']}\n");
            $arquivoLog = null;
        }

        parent::destroy($id);
        return true;
    }

}

$handler = new FileSessionHandler();
session_set_save_handler(
    array($handler, 'open'),
    array($handler, 'close'),
    array($handler, 'read'),
    array($handler, 'write'),
    array($handler, 'destroy'),
    array($handler, 'gc')
);

// a linha a seguir evita comportamentos não esperados ao usar objetos como manipuladores de gravação
register_shutdown_function('session_write_close');

