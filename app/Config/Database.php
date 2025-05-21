<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $conn;

    public function connect() {
        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->conn = new PDO(
                "sqlsrv:Server=$host;Database=$dbName",
                $user,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
            exit;
        }
    }
}
