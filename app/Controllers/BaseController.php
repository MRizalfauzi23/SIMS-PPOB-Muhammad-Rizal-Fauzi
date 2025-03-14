<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class BaseController extends Controller
{
    public $userData;
    public $userBalance;

    public function __construct()
    {
        helper(['session']); 

        $this->userData = $this->getUserProfile();
        $this->userBalance = $this->getUserBalance();
    }

    private function getUserProfile()
    {
        $session = session();
        if (!$session->has('user_token')) {
            return null;
        }

        $token = $session->get('user_token');
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Accept'        => 'application/json'
                ],
                'timeout' => 5, 
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['data'] ?? null; 
        } catch (HTTPException $e) {
            log_message('error', 'Gagal mengambil profil pengguna: ' . $e->getMessage());
            return null;
        }
    }

    // Fungsi mengambil saldo dari API
    private function getUserBalance()
    {
        $session = session();
        if (!$session->has('user_token')) {
            return 0;
        }

        $token = $session->get('user_token');
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Accept'        => 'application/json'
                ],
                'timeout' => 5,
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['balance'] ?? 0; 
        } catch (HTTPException $e) {
            log_message('error', 'Gagal mengambil saldo pengguna: ' . $e->getMessage());
            return 0;
        }
    }
}
