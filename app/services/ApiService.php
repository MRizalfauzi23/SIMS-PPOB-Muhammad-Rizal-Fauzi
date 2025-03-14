<?php

namespace App\Services;

use CodeIgniter\HTTP\Client;

class ApiService
{
    protected $client;
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('API_BASE_URL');
        $this->client = service('curlrequest');
    }

    public function post($endpoint, $data)
    {
        try {
            $response = $this->client->post($this->apiBaseUrl . $endpoint, [
                'json' => $data
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => 'Gagal terhubung ke API.'];
        }
    }

    public function get($endpoint, $headers = [])
    {
        try {
            $response = $this->client->get($this->apiBaseUrl . $endpoint, [
                'headers' => $headers
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => 'Gagal mengambil data dari API.'];
        }
    }
}
