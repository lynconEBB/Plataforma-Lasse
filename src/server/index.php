<?php

require '../../vendor/autoload.php';

use Lasse\LPM\Control\AtividadeControl;
use Lasse\LPM\Control\CompraControl;
use Lasse\LPM\Control\CondutorControl;
use Lasse\LPM\Control\GastoControl;
use Lasse\LPM\Control\ItemControl;
use Lasse\LPM\Control\ProjetoControl;
use Lasse\LPM\Control\TarefaControl;
use Lasse\LPM\Control\UsuarioControl;
use Lasse\LPM\Control\VeiculoControl;
use Lasse\LPM\Control\ViagemControl;
use Lasse\LPM\Services\OdtParser;


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
        '/menu/atividadePlanejada' => ['classe' => AtividadeControl::class,'parametro'=>'listaAtividadesPlanejadas'],
        '/menu/atividadeNaoPlanejada' => ['classe' => AtividadeControl::class,'parametro'=>'listaAtividadesNaoPlanejadas'],
        '/acaoAtividade' => ['classe' => AtividadeControl::class,'parametro'=>''],
        '/menu/compra' => ['classe' => CompraControl::class,'parametro'=>'listaCompras'],
        '/acaoCompra' => ['classe' => CompraControl::class,'parametro'=>''],
        '/menu/compra/item' => ['classe' => ItemControl::class,'parametro'=>'listaItensCompra'],
        '/menu/item' => ['classe' => ItemControl::class,'parametro'=>'listaItens'],
        '/acaoItem' => ['classe' => ItemControl::class,'parametro'=>''],
        '/menu/formulario' => ['classe' => OdtParser::class,'parametro'=>'criaFormulario'],
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

    public function instanciaClasse()
    {

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