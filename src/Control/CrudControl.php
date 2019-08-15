<?php

namespace Lasse\LPM\Control;

abstract class CrudControl{
    public $DAO;
    public $metodo;
    public $url;

    abstract public function processaRequisicao();

    public function respostaSucesso($mensagem,$dados= null,$requisitor = null) {
        header('Content-Type: application/json');
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