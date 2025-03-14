<?php


namespace App\Controllers;

use App\Models\BannerModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\CURLRequest;

class DashboardController extends BaseController
{
    use ResponseTrait;
    
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
        $this->token  = session()->get('token');

        if (!$this->token) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function index()
    {
        // Pastikan user sudah login
        if (!session()->get('token')) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Ambil data dari API
        $data['profile'] = $this->fetchProfile();
        $data['balance'] = $this->fetchBalance();
        $data['banners'] = $this->fetchBanners(); // Ambil banner dari API
    
        return view('dashboard/dashboard', $data);
    }
    private function fetchBanners()
    {
        $token = session()->get('token');
        if (!$token) {
            return [];
        }
    
        try {
            $client = service('curlrequest');
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/banner', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false // Agar tidak error fatal jika API gagal
            ]);
    
            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? []; // Pastikan hanya mengambil data yang relevan
    
        } catch (\Exception $e) {
            return []; // Jika gagal, kembalikan array kosong
        }
    }
        

    private function fetchProfile()
    {
        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchBalance()
    {
        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data']['balance'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getBalance()
    {
        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $this->respond($result, 200);
        } catch (\Exception $e) {
            return $this->respond(['message' => 'Gagal mengambil saldo', 'error' => $e->getMessage()], 500);
        }
    }



    public function profile()
    {
        $client = service('curlrequest');
        $token = session()->get('token');

        if (!$token) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json'
                ]
            ]);

            $profileData = json_decode($response->getBody(), true);
            $data['user'] = $profileData['data'] ?? [];
        } catch (\Exception $e) {
            return view('profile', ['user' => [], 'error' => 'Gagal mengambil data profil.']);
        }

        return view('dashboard/profile', $data);
    }

    public function uploadImage()
    {
        $client = service('curlrequest');
        $token = session()->get('token');
        $file  = $this->request->getFile('file');

        if (!$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            return redirect()->to('/profile')->with('error', 'Format file harus JPG atau PNG.');
        }

        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login kembali.');
        }

        try {
            $response = $client->request('PUT', 'https://take-home-test-api.nutech-integrasi.com/profile/image', [
                'headers'   => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json'
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file->getTempName(), 'r'),
                        'filename' => $file->getName()
                    ]
                ]
            ]);

            return $response->getStatusCode() == 200
                ? redirect()->to('/profile')->with('success', 'Foto profil berhasil diperbarui.')
                : redirect()->to('/profile')->with('error', 'Gagal mengupload foto.');
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Terjadi kesalahan saat mengupload foto.');
        }
    }

    public function updateProfile()
    {
        $token = session()->get('token');

        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $client = \Config\Services::curlrequest();
        $data   = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name')
        ];

        try {
            $response = $client->request('PUT', 'https://take-home-test-api.nutech-integrasi.com/profile/update', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json'
                ],
                'json'    => $data
            ]);

            return $response->getStatusCode() == 200
                ? redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.')
                : redirect()->to('/profile')->with('error', 'Gagal memperbarui profil.');
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function transaction()
    {
        $client = service('curlrequest');
        $token = session()->get('token');

        if (!$token) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/transaction/history', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json'
                ],
                'query'   => [
                    'offset' => 0,
                    'limit'  => 10
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            $data['transactions'] = $result['data']['records'] ?? [];
        } catch (\Exception $e) {
            $data['transactions'] = [];
            $data['error_message'] = 'Gagal mengambil data transaksi. Silakan coba lagi.';
        }

        return view('dashboard/transaction', $data);
    }

   
}
