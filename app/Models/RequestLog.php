<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class RequestLog {
    private $conn;
    private $table = 'RequestLogs';

    public $id;
    public $endpoint;
    public $method;
    public $responseData;
    public $createdAt;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create() {
        $query = "INSERT INTO {$this->table} (Endpoint, Method, ResponseData) VALUES (:endpoint, :method, :responseData)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':endpoint', $this->endpoint);
        $stmt->bindParam(':method', $this->method);
        $stmt->bindParam(':responseData', $this->responseData);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY CreatedAt DESC";
        $stmt = $this->conn->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normaliza las claves a minúsculas para frontend
        $normalized = array_map(function($row) {
            return [
            'id' => $row['Id'],
            'endpoint' => $row['Endpoint'],
            'method' => $row['Method'],
            'responseData' => $row['ResponseData'],
            'createdAt' => $row['CreatedAt'],
            ];
        }, $results);

        return $normalized;
    }


    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET Endpoint = :endpoint, Method = :method, ResponseData = :responseData WHERE Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':endpoint', $data['endpoint']);
        $stmt->bindParam(':method', $data['method']);
        $stmt->bindParam(':responseData', $data['responseData']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();  // Retorna la cantidad de filas eliminadas
    }

}
