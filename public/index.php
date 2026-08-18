<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Carrega o Autoloader do Composer
require __DIR__.'/../vendor/autoload.php';

// Inicializa a aplicação Laravel e processa a requisição HTTP
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());