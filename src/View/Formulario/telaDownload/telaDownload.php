<?php

use Lasse\LPM\Control\FormularioControl;
use Lasse\LPM\Control\UsuarioControl;

$requisitor = UsuarioControl::autenticar();
$url = trim("/api/formularios/download/4",'/');

if (strpos($url,"?") === 0) {
    $url = "";
} elseif (strpos($url,"?") ){
    $url = substr($url,0,strpos($url,"?"));
}

$url = explode('/',$url);

$formularioControl = new FormularioControl($url);

?>
<script type="text/javascript">
    location = '/viagem/11';
</script>

