<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use GuzzleHttp\Client;


class ProfileController extends BaseController
{
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
        $token = session()->get('token');
        $file  = $this->request->getFile('file');
    
        if (!$file || !$file->isValid()) {
            return redirect()->to('/profile')->with('error', 'Tidak ada file yang diunggah atau file tidak valid.');
        }
    
        // Periksa ekstensi file yang diperbolehkan
        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        if (!in_array($file->getClientMimeType(), $allowedMimeTypes)) {
            return redirect()->to('/profile')->with('error', 'Format file harus JPG atau PNG.');
        }
    
        if (!$token) {
            return redirect()->to('/profile')->with('error', 'Autentikasi gagal, silakan login kembali.');
        }
    
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('PUT', 'https://take-home-test-api.nutech-integrasi.com/profile/image', [
                'headers'   => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'multipart' => [
                    [
                        'name'     => 'file', // Sesuaikan dengan API
                        'contents' => fopen($file->getTempName(), 'r'),
                        'filename' => $file->getName()
                    ]
                ]
            ]);
    
            $status = $response->getStatusCode();
            $body   = json_decode($response->getBody()->getContents(), true);
    
            return ($status == 200)
                ? redirect()->to('/profile')->with('success', 'Foto profil berhasil diperbarui.')
                : redirect()->to('/profile')->with('error', 'Gagal mengupload: ' . ($body['message'] ?? 'Terjadi kesalahan.'));
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
}
