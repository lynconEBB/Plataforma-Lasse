<?php
    require_once '../Control/FormularioControl.php';

    $formControl = new FormularioControl($_FILES["arquivo"]["tmp_name"]);
    echo '<style>';
    echo 'table{border-collapse: collapse;}';
    foreach ($formControl->get_estilos() as $estilo => $atributos){
        if(count($atributos)>0){
            echo '.'.$estilo.'{';
            foreach ($atributos as $nome =>$valor){
                echo $nome.':'.$valor.'; ';
            }
            echo '} ';
        }
    }
    echo '</style>';
    $html = $formControl->get_html();
    $html = str_replace(':',':<input type="text">',$html);
    echo $html;
    echo '<script src="../server/js/jquey.js"></script>';
    echo '<script src="../server/js/funcoesLogin.js"></script>';