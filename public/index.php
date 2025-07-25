<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Modo mantenimiento
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoload Composer
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

// Captura la petición HTTP
$request = Request::capture();

// Maneja la petición y obtiene respuesta
$response = $kernel->handle($request);

// Envía la respuesta al navegador
$response->send();

// Finaliza la petición
$kernel->terminate($request, $response);
