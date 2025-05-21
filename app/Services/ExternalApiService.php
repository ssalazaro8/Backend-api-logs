<?php
namespace App\Services;

class ExternalApiService {
    private $baseUrl = 'https://jsonplaceholder.typicode.com';

    private function fetchData($endpoint) {
        $url = $this->baseUrl . $endpoint;

        // Simular error en 20% de las peticiones
        if (rand(1, 10) > 8) {
            throw new \Exception("Error simulado en API externa");
        }

        $response = file_get_contents($url);
        if ($response === false) {
            throw new \Exception("Error al consumir API externa");
        }
        return json_decode($response, true);
    }

    public function getUsers() {
        return $this->fetchData('/users');
    }

    public function getPosts() {
        return $this->fetchData('/posts');
    }

    public function getAlbumsByUserId($userId) {
        return $this->fetchData("/albums?userId={$userId}");
    }
}
