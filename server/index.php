<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../Services/Autoload.php';

class RouteController{

    private $rotas = [
        '/login' => ['classe' => UsuarioControl::class,'parametro'=>'login'],
        '/acaoUsuario' => ['classe' => UsuarioControl::class,'parametro'=>''],
        '/menu/usuario' => ['classe' => UsuarioControl::class,'parametro'=>'perfil'],
        ];
    private $caminho;

    public function __construct($caminho)
    {
        $this->caminho = $caminho;

        if(!array_key_exists($this->caminho,$this->rotas)) {
            http_response_code(404);
            exit();
        }else{
            $this->instanciaClasse();
        }
    }

    public function instanciaClasse(){
        $classeController = $this->rotas[$this->caminho]['classe'];
        $parametro = $this->rotas[$this->caminho]['parametro'];

        /**
         * @var CrudControl $controler
         */
        $controler = new $classeController;
        if($parametro != ''){
            $controler->processaRequisicao($parametro);
        }
    }
}

new RouteController($_SERVER['PATH_INFO']);