<?php

use src\services\CalculationService;

function autoload()
{
    require_once __DIR__ . '/vendor/autoload.php';

    spl_autoload_register(function ($class){
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    });
}

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
autoload();
CalculationService::run();