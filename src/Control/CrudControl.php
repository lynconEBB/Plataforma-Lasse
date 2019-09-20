<?php

namespace Lasse\LPM\Control;

abstract class CrudControl{
    protected $DAO;
    protected $metodo;
    protected $url;
    protected $requisitor;

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
        header("Accept: application/json");
        http_response_code(200);
        $resposta = ["status" => "sucesso" , "mensagem" => $mensagem];

        if (!is_null($requisitor)) {
            $resposta["requisitor"] = $requisitor;
        }
        if (!is_null($dados)) {
            if (is_array($dados)) {

                $array = array();
                foreach ($dados as $obj) {
                    if (!is_array($obj)) {
                        $array[] = $obj->toArray();
                    } else {
                        $array = $dados;
                        continue;
                    }
                }
                $resposta["dados"] = $array;
            } elseif (is_bool($dados) || is_string($dados)) {
                $resposta["dados"] = $dados;
            } else {
                $resposta["dados"] = $dados->toArray();
            }

        }
        echo json_encode($resposta);
    }

}
