<?php
use Slim\App;
use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\AlbumController;
use App\Controllers\LogController;

return function (App $app) {
    // Listar usuarios
    $app->get('/users', [UserController::class, 'listUsers']);

    // Listar publicaciones (posts)
    $app->get('/posts', [PostController::class, 'listPosts']);

    // Consultar álbumes filtrados por userId como query param
    $app->get('/albums[/{userId}]', [AlbumController::class, 'getAlbums']);

    // Listar registros de peticiones
    $app->get('/logs', [LogController::class, 'listLogs']);

    // Crear registro de petición
    $app->post('/logs', [LogController::class, 'createLog']);

    // Editar registro de petición
    $app->put('/logs/{id}', [LogController::class, 'editLog']);

    // Eliminar registro de petición
    $app->delete('/logs/{id}', [LogController::class, 'deleteLog']);
};
