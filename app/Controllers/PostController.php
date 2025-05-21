<?php
namespace App\Controllers;

use App\Services\ExternalApiService;
use App\Models\RequestLog;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController {
    private $apiService;

    public function __construct() {
        $this->apiService = new ExternalApiService();
    }

    public function listPosts(Request $request, Response $response) {
        try {
            $posts = $this->apiService->getPosts();

            $log = new RequestLog();
            $log->endpoint = '/posts';
            $log->method = 'GET';
            $log->responseData = json_encode($posts);
            $log->create();

            $response->getBody()->write(json_encode($posts));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $error = ['error' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
