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

    public function getAlbums(Request $request, Response $response, $args) {
        // Obtener userId de segmento de ruta o query param
        $userId = $args['userId'] ?? $request->getQueryParams()['userId'] ?? null;

        // Validar que userId exista y sea numérico
        if (!$userId || !is_numeric($userId)) {
            return $this->errorResponse($response, 'El parámetro userId es requerido y debe ser numérico', 400);
        }

        try {
            $albums = $this->apiService->getAlbumsByUserId($userId);

            $this->logRequest("/albums" . ($args['userId'] ?? "?userId={$userId}"), 'GET', $albums);

            return $this->successResponse($response, $albums);
        } catch (\Exception $e) {
            $this->logRequest("/albums" . ($args['userId'] ?? "?userId={$userId}"), 'GET', ['error' => $e->getMessage()]);
            return $this->errorResponse($response, $e->getMessage(), 500);
        }
    }

    private function successResponse(Response $response, $data) {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

    private function errorResponse(Response $response, string $message, int $status) {
        $error = ['error' => $message];
        $response->getBody()->write(json_encode($error));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

    private function logRequest(string $endpoint, string $method, $data) {
        $log = new RequestLog();
        $log->endpoint = $endpoint;
        $log->method = $method;
        $log->responseData = json_encode($data);
        $log->create();
    }
}
