<?php

namespace App\Controllers;

use App\Services\AuthService;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function registerForm()
    {
        return view('auth/register-views');
    }

    public function register()
    {
        $request = service('request');
        $session = session();
    
        // Ambil data dari request JSON atau POST biasa
        $jsonData = $request->getJSON(true);
        $email       = $jsonData['email'] ?? $request->getPost('email');
        $firstName   = $jsonData['first_name'] ?? $request->getPost('first_name');
        $lastName    = $jsonData['last_name'] ?? $request->getPost('last_name');
        $password    = $jsonData['password'] ?? $request->getPost('password');
        $confirmPass = $jsonData['confirm_password'] ?? $request->getPost('confirm_password');
    
        // Validasi form tidak boleh kosong
        if (!$email || !$firstName || !$lastName || !$password || !$confirmPass) {
            return $this->response->setJSON(['status' => 1, 'message' => '']);
        }
    
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Format email tidak valid.']);
        }
    
        // Validasi panjang password (minimal 8 karakter dengan kombinasi huruf & angka)
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.']);
        }
    
        // Validasi konfirmasi password
        if ($password !== $confirmPass) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Konfirmasi password tidak cocok.']);
        }
    
        // Data yang akan dikirim ke API
        $requestData = [
            'email'      => $email,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'password'   => $password,
        ];
    
        try {
            $response = $this->authService->register($requestData);
    
            if (isset($response['status']) && $response['status'] == 0 && str_contains(strtolower($response['message']), 'berhasil')) {
                return $this->response->setJSON(['status' => 0, 'message' => 'Registrasi berhasil! Silakan login.']);
            }
    
            log_message('error', 'Registrasi gagal: ' . ($response['message'] ?? 'Tidak ada pesan error.'));
            return $this->response->setJSON(['status' => 0, 'message' => $response['message'] ?? 'Registrasi gagal.']);
    
        } catch (\Exception $e) {
            log_message('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 1, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    public function loginForm()
    {
        return view('auth/login-views');
    }

    public function login()
    {
        $request = service('request');
        $session = session();
    
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        $response = $this->authService->login([
            'email' => $email,
            'password' => $password,
        ]);
    
        if ($response['status'] === true) {
            // Simpan token ke dalam sesi
            $session->set('logged_in', true);
            $session->set('token', $response['data']['token']);
            $session->setFlashdata('success', 'Login berhasil! Selamat datang di dashboard.');
    
            return redirect()->to('/dashboard'); 
        }

        $session->setFlashdata('error', $response['message'] ?? 'Email atau password salah.');
    
        return redirect()->back();
    }
   

    public function logout()
    {
        $session = session();
        if (!$session->has('logged_in')) {
            return redirect()->to('/')->with('error', 'Anda belum login.');
        }
    
      
        log_message('info', 'User logout: ' . ($session->get('token') ?? 'No token'));
    
       
        $session->remove(['logged_in', 'token']);
        $session->destroy();
    
        return redirect()->to('/')->with('success', 'Anda telah logout dengan sukses.');
    }
    
}
