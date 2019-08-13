<?php

require 'vendor/autoload.php';

use Lasse\LPM\Control\AtividadeControl;
use Lasse\LPM\Control\CompraControl;
use Lasse\LPM\Control\CondutorControl;
use Lasse\LPM\Control\CrudControl;
use Lasse\LPM\Control\GastoControl;
use Lasse\LPM\Control\ItemControl;
use Lasse\LPM\Control\ProjetoControl;
use Lasse\LPM\Control\TarefaControl;
use Lasse\LPM\Control\UsuarioControl;
use Lasse\LPM\Control\VeiculoControl;
use Lasse\LPM\Control\ViagemControl;

class Router{

    private $url;

    public function __construct()
    {
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $this->formataURL($_SERVER['SCRIPT_URL']);

        if ($this->url[0] == 'api') {
            if (count($this->url) > 1) {
                $this->decideControler();
            } else {
                http_response_code(400);
            }
        } else {
            echo 'ola';
        }
    }

    private function decideControler()
    {
        switch ($this->url[1]) {
            case 'users':
                $controler = new UsuarioControl($this->url);
                break;
        }
    }

    private function formataURL(string $url):array
    {
        $url = trim($url,'/');
        $partes = explode('/',$url);
        return $partes;
    }
}

new Router();