<?php
namespace App\Controllers;

use App\Services\ExternalApiService;
use App\Models\RequestLog;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlbumController {
    private $apiService;

    public function __construct() {
        $this->apiService = new ExternalApiService();
    }

    public function getAlbumsByUser(Request $request, Response $response, $args) {
        try {
            $userId = $args['userId'];
            $albums = $this->apiService->getAlbumsByUserId($userId);

            // Guardar log exitoso
            $log = new RequestLog();
            $log->endpoint = "/albums?userId={$userId}";
            $log->method = 'GET';
            $log->responseData = json_encode($albums);
            $log->create();

            $response->getBody()->write(json_encode($albums));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Guardar log de error
            $log = new RequestLog();
            $log->endpoint = "/albums?userId={$userId}";
            $log->method = 'GET';
            $log->responseData = json_encode(['error' => $e->getMessage()]);
            $log->create();

            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
