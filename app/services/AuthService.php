<?php

namespace App\Services;

use CodeIgniter\HTTP\Client;

class AuthService
{
    protected $client;
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = getenv('API_BASE_URL');
        $this->client = service('curlrequest');
    }

    /**
     * Register user
     */
    public function register(array $data)
    {
        try {
            $response = $this->client->post("{$this->apiBaseUrl}/registration", [
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);

            // Pastikan response memiliki 'message' untuk feedback yang lebih jelas
            $message = $result['message'] ?? 'Registrasi berhasil.';

            if ($statusCode == 200 && isset($result['data'])) {
                return [
                    'status'  => true,
                    'message' => $message,
                    'data'    => $result['data']
                ];
            }

            return [
                'status'  => false,
                'message' => $message
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error saat registrasi: ' . $e->getMessage());
            return [
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menghubungkan ke server. Silakan coba lagi.'
            ];
        }
    }

    /**
     * Login user
     */
    public function login(array $data)
    {
        try {
            $response = $this->client->post("{$this->apiBaseUrl}/login", [
                'json' => $data
            ]);
    
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $result = json_decode($body, true);
    
            // Pastikan JSON ter-decode dengan benar
            if (!is_array($result)) {
                log_message('error', "Login API response is not valid JSON: " . $body);
                return [
                    'status'  => false,
                    'message' => 'Kesalahan server: Respons tidak valid.'
                ];
            }
    
            // Jika status kode 200 dan ada token dalam respons, login berhasil
            if ($statusCode === 200 && isset($result['data']['token'])) {
                return [
                    'status'  => true,
                    'message' => 'Login berhasil!',
                    'data'    => $result['data']
                ];
            }
    
            // Tangani kasus jika status API menunjukkan kesalahan tertentu
            $errorMessage = $result['message'] ?? 'Login gagal. Silakan periksa kembali data Anda.';
    
            return [
                'status'  => false,
                'message' => $errorMessage
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Jika API mengembalikan status 400/401/403
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $errorData = json_decode($body, true);
    
            log_message('error', "Login ClientException: HTTP $statusCode - " . $body);
    
            $errorMessage = $errorData['message'] ?? 'Login gagal. Periksa kembali email dan password Anda.';
    
            return [
                'status'  => false,
                'message' => $errorMessage
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Jika terjadi kesalahan server (500, 502, 503)
            log_message('error', "Login ServerException: " . $e->getMessage());
    
            return [
                'status'  => false,
                'message' => 'Terjadi kesalahan di server. Silakan coba beberapa saat lagi.'
            ];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Jika terjadi error jaringan atau API tidak bisa diakses
            log_message('error', "Login RequestException: " . $e->getMessage());
    
            return [
                'status'  => false,
                'message' => 'Gagal menghubungkan ke server. Periksa koneksi internet Anda.'
            ];
        } catch (\Exception $e) {
            // Tangani semua error lainnya
            log_message('error', "Login Exception: " . $e->getMessage());
    
            return [
                'status'  => false,
                'message' => 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi.'
            ];
        }
    }
}    