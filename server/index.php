<?php

require '../Services/Autoload.php';

class RouteController{

    private $rotas = [
        '/login' => ['classe' => UsuarioControl::class,'parametro'=>'login'],
        '/acaoUsuario' => ['classe' => UsuarioControl::class,'parametro'=>''],
        '/menu/usuario' => ['classe' => UsuarioControl::class,'parametro'=>'perfil'],
        '/menu/projeto' => ['classe' => ProjetoControl::class,'parametro'=>'listaProjetos'],
        '/acaoProjeto' => ['classe' => ProjetoControl::class,'parametro'=>''],
        '/menu/tarefa' => ['classe' => TarefaControl::class,'parametro'=>'listaTarefas'],
        '/acaoTarefa' => ['classe' => TarefaControl::class,'parametro'=>''],
        '/menu/viagem' => ['classe' => ViagemControl::class,'parametro'=>'listaViagens'],
        '/acaoViagem' => ['classe' => ViagemControl::class,'parametro'=>''],
        '/menu/viagem/cadastro' => ['classe' => ViagemControl::class,'parametro'=>'cadastraViagem'],
        '/menu/veiculo' => ['classe' => VeiculoControl::class,'parametro'=>'listaVeiculos'],
        '/acaoVeiculo' => ['classe' => VeiculoControl::class,'parametro'=>''],
        '/menu/condutor' => ['classe' => CondutorControl::class,'parametro'=>'listaCondutores'],
        '/acaoCondutor' => ['classe' => CondutorControl::class,'parametro'=>''],
        '/menu/gasto' => ['classe' => GastoControl::class,'parametro'=>'listaGastosGeral'],
        '/menu/viagem/gastos' => ['classe' => GastoControl::class,'parametro'=>'listaGastosViagem'],
        '/acaoGasto' => ['classe' => GastoControl::class,'parametro'=>''],
        '/menu/AtividadePlanejada' => ['classe' => AtividadeControl::class,'parametro'=>'listaAtividadesPlanejadas'],
        '/acaoAtividade' => ['classe' => AtividadeControl::class,'parametro'=>''],
        ];
    private $caminho;

    public function __construct($caminho)
    {
        $this->caminho = $caminho;

        if(!array_key_exists($this->caminho,$this->rotas)) {
            require '../View/errorPages/erro404.php';
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