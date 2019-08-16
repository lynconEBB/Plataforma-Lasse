<?php

namespace Lasse\LPM\Control;

abstract class CrudControl{
    public $DAO;
    public $metodo;
    public $url;

    public function __construct($url)
    {
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->url = $url;
        $this->processaRequisicao();
    }

    abstract public function processaRequisicao();

    public function respostaSucesso($mensagem,$dados= null,$requisitor = null)
    {
        header("Content-type: application/json; charset=utf-8");
        http_response_code(200);
        $resposta = ["status" => "sucesso" , "mensagem" => $mensagem];
        if (!is_null($requisitor)) {
            $resposta["requisitor"] = $requisitor;
        }
        if (!is_null($dados)) {
            $resposta["dados"] = $dados;
        }
        echo json_encode($resposta);
    }

}