<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                http_response_code(404);
            }
        } else {
            if ($this->url[0] == "" && count($this->url) == 1) {
                require 'src/View/Usuario/telaLogin.html';
            }
        }
    }

    private function decideControler()
    {
        switch ($this->url[1]) {
            case 'users':
                $controler = new UsuarioControl($this->url);
                break;
            case 'projetos':
                $controler = new ProjetoControl($this->url);
                break;
            case 'tarefas':
                $controler = new TarefaControl($this->url);
                break;
            case 'atividades':
                $controler = new AtividadeControl($this->url);
                break;
            case 'compras':
                $controler = new CompraControl($this->url);
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