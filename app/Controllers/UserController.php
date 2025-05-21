<?php
namespace App\Controllers;

use App\Services\ExternalApiService;
use App\Models\RequestLog;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    private $apiService;

    public function __construct() {
        $this->apiService = new ExternalApiService();
    }

    public function listUsers(Request $request, Response $response) {
        try {
            $users = $this->apiService->getUsers();

            // Guardar log exitoso
            $log = new RequestLog();
            $log->endpoint = '/users';
            $log->method = 'GET';
            $log->responseData = json_encode($users);
            $log->create();

            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // Guardar log de error
            $log = new RequestLog();
            $log->endpoint = '/users';
            $log->method = 'GET';
            $log->responseData = json_encode(['error' => $e->getMessage()]);
            $log->create();

            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
