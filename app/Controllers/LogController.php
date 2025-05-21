<?php
namespace App\Controllers;

use App\Models\RequestLog;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogController {
    private $logModel;

    public function __construct() {
        $this->logModel = new RequestLog();
    }

    public function listLogs(Request $request, Response $response) {
        $logs = $this->logModel->getAll();
        $response->getBody()->write(json_encode($logs));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createLog(Request $request, Response $response) {
        $data = $request->getParsedBody();

        $log = new RequestLog();
        $log->endpoint = $data['endpoint'] ?? '';
        $log->method = $data['method'] ?? '';
        $log->responseData = $data['responseData'] ?? ''; // CORRECCIÓN: Asignar directamente

        if ($log->create()) {
            $response->getBody()->write(json_encode(['message' => 'Log creado']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'No se pudo crear']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function editLog(Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();

        // CORRECCIÓN: Asignar directamente en lugar de codificar
        $data['responseData'] = $data['responseData'] ?? '';

        $updated = $this->logModel->update($id, $data);

        if ($updated) {
            $response->getBody()->write(json_encode(['message' => 'Log actualizado']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'No se pudo actualizar']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteLog(Request $request, Response $response, $args) {
        $id = $args['id'];
        $deleted = $this->logModel->delete($id);

        if ($deleted) {
            $response->getBody()->write(json_encode(['message' => 'Log eliminado']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'No se pudo eliminar']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
