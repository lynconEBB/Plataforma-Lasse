<?php

namespace Lasse\LPM\Services;

class Mensagem{
    public static function exibir($tipo){
        if(isset($_SESSION[$tipo])){
            echo "<div style='z-index: 10' class='alert alert-{$tipo} alert-dismissible'>";
                echo "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
                echo $_SESSION[$tipo];
            echo "</div>";

            unset($_SESSION[$tipo]);
        }
    }
}