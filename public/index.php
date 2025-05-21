<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

// Middleware para parsear JSON
$app->addBodyParsingMiddleware();

// Middleware para CORS
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Ruta raíz para prueba
$app->get('/', function ($request, $response) {
    $response->getBody()->write("API Slim funcionando!");
    return $response;
});

// Cargar rutas
(require __DIR__ . '/../app/Routes/api.php')($app);

$app->run();
