<?php

namespace App\Services;

use CodeIgniter\HTTP\Client;

class ApiServiceTest
{
    protected $client;
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('API_BASE_URL');
        $this->client = service('curlrequest');
    }

    public function testGetBanners()
    {
        try {
            $response = $this->client->get($this->apiBaseUrl . '/banner');
            log_message('info', 'API Response Status Code: ' . $response->getStatusCode());
            log_message('info', 'API Response Body: ' . $response->getBody());
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching banners: ' . $e->getMessage());
            return ['error' => 'Gagal mengambil data dari API.'];
        }
    }
}
