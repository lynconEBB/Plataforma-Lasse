<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Lasse\LPM\Control\AtividadeControl;
use Lasse\LPM\Control\CompraControl;
use Lasse\LPM\Control\CondutorControl;
use Lasse\LPM\Control\FormularioControl;
use Lasse\LPM\Control\GastoControl;
use Lasse\LPM\Control\ItemControl;
use Lasse\LPM\Control\ProjetoControl;
use Lasse\LPM\Control\TarefaControl;
use Lasse\LPM\Control\UsuarioControl;
use Lasse\LPM\Control\VeiculoControl;
use Lasse\LPM\Control\ViagemControl;
use Lasse\LPM\Erros\NotFoundException;

class Router{

    private $url;
    private $metodo;

    public function __construct()
    {
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $this->formataURL($_SERVER['REQUEST_URI']);
        
        if ($this->url[0] == 'api') {
            $this->decideControler();
        } else {
            $this->decideView();
        }
    }

    private function decideView()
    {
        if ($this->url[0] == "" && count($this->url) == 1) {
            require 'src/View/Usuario/telaLogin/telaLogin.html';
        }
        // /dashboard/user/{idUsuario}
        elseif ($this->url[0] == "dashboard" && $this->url[1] == "user" && $this->url[2] == (int)$this->url[2] && count($this->url) == 3) {
            require 'src/View/Usuario/telaDashboard/telaDashboard.html';
        }
        // /perfil/user/{idUsuario}
        elseif ($this->url[0] == "perfil" && $this->url[1] == "user" && $this->url[2] == (int)$this->url[2] && count($this->url) == 3){
            require "src/View/Usuario/telaPerfil/telaPerfil.html";
        }
        // /projetos/user/{idUsuario}
        elseif ($this->url[0] == "projetos" && $this->url[1] == "user" && $this->url[2] == (int)$this->url[2] && count($this->url) == 3){
            require "src/View/Projeto/telaDashProjetos/telaProjetos.html";
        }
        // /projeto/{idUsuario}
        elseif ($this->url[0] == "projeto" && $this->url[1] == (int)$this->url[1] && count($this->url) == 2){
            require "src/View/Projeto/telaProjeto/telaProjeto.html";
        }
        // /tarefa/{idTarefa}
        elseif ($this->url[0] == "tarefa" && $this->url[1] == (int)$this->url[1] && count($this->url) == 2){
            require "src/View/Tarefa/telaTarefa/telaTarefa.html";
        }
        // /viagem/{idViagem}
        elseif ($this->url[0] == "viagem" && $this->url[1] == (int)$this->url[1] && count($this->url) == 2){
            require "src/View/Viagem/telaViagem/telaViagem.html";
        }
        // /compra/{idCompra}
        elseif ($this->url[0] == "compra" && $this->url[1] == (int)$this->url[1] && count($this->url) == 2){
            require "src/View/Compra/telaCompra/telaCompra.html";
        }
        // /atividade/{idAtividade}
        elseif ($this->url[0] == "atividade" && $this->url[1] == (int)$this->url[1] && count($this->url) == 2){
            require "src/View/Atividade/telaAtividade/telaAtividade.html";
        }
        // /senhaAlterar
        elseif ($this->url[0] == "senhaAlterar" && count($this->url) == 1){
            require "src/View/Usuario/telaAlterarSenha/telaAlterarSenha.html";
        }
        // /imprevistos/user/{idUsuario}
        elseif ($this->url[0] == "imprevistos" && $this->url[1] == "user" && $this->url[2] == (int)$this->url[2] && count($this->url) == 3){
            require "src/View/Atividade/telaImprevistos/telaImprevistos.html";
        }
        // /erro/permissao
        elseif ($this->url[0] == "erro" && $this->url[1] == "permissao" && count($this->url) == 2) {
            require "src/View/errorPages/erroSemAcesso.html";
        }
        // /erro/naoEncontrado
        elseif ($this->url[0] == "erro" && $this->url[1] == "naoEncontrado" && count($this->url) == 2) {
            require "src/View/errorPages/erro404.html";
        }
        else{
            require "src/View/errorPages/erro404.html";
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
            case 'itens':
                $controler = new ItemControl($this->url);
                break;
            case 'viagens':
                $controler = new ViagemControl($this->url);
                break;
            case 'condutores':
                $controler = new CondutorControl($this->url);
                break;
            case 'veiculos':
                $controler = new VeiculoControl($this->url);
                break;
            case 'gastos':
                $controler = new GastoControl($this->url);
                break;
            case 'formularios':
                $controler = new FormularioControl($this->url);
                break;
            default:
                throw new NotFoundException("URL não encontrada");
        }
    }

    private function formataURL(string $url):array
    {
        $url = trim($url,'/');

        if (strpos($url,"?") === 0) {
            $url = "";
        } elseif (strpos($url,"?") ){
            $url = substr($url,0,strpos($url,"?"));
        }

        $partes = explode('/',$url);
        return $partes;
    }
}

new Router();
