<?php
$zip = new ZipArchive();
$res = $zip->open($_SERVER['DOCUMENT_ROOT']."/assets/files/default/requisicaoViagem.odt");

if ($res === true) {
    $zip->extractTo($_SERVER['DOCUMENT_ROOT']."/test");
    $zip->close();
    echo "deu<br>";
} else {
    echo "nao deu<br>";
}
var_dump($zip);