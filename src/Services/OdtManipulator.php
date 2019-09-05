<?php


namespace Lasse\LPM\Services;


use Exception;
use ZipArchive;

class OdtManipulator
{
    public function __construct($arquivo,$pasta)
    {
        $this->unzipODT($arquivo,$pasta);
    }

    public function unzipODT($arquivo,$pasta)
    {


    }
}