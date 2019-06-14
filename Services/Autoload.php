<?php
    const MVC = array('Model', 'View', 'Control', 'Dao');

    spl_autoload_register(function ($classe) {
        $partes = preg_split('/(?=[A-Z])/', $classe, -1, PREG_SPLIT_NO_EMPTY);
        $pasta = end($partes);

        if (!in_array($pasta,MVC)) {
            $pasta = "Services";
        }

        $caminho = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$pasta.DIRECTORY_SEPARATOR.$classe.".php";
        require $caminho;
    });
