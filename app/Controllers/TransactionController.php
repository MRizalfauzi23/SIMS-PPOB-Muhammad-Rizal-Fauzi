<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class TransactionController extends Controller
{
    use ResponseTrait;
    protected $format = 'json';
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = service('curlrequest');
        $this->token  = session()->get('token');
    }

    public function transaction()
    {
        if (!$this->token) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $data['profile'] = $this->fetchProfile();
        $data['balance'] = $this->fetchBalance();

        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/transaction/history', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json'
                ],
                'http_errors' => false,
                'query' => [
                    'limit' => 10,
                    'offset' => 0
                ]
            ]);

            $status = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);

            if ($status === 200 && isset($result['data']['records'])) {
                $data['transactions'] = $result['data']['records'];
            } else {
                $data['transactions'] = [];
                $data['error_message'] = $result['message'] ?? 'Gagal mengambil data transaksi.';
            }

        } catch (\Exception $e) {
            $data['transactions'] = [];
            $data['error_message'] = 'Terjadi kesalahan.';
        }

        return view('dashboard/transaction', $data);
    }

    private function fetchProfile()
    {
        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept'        => 'application/json'
                ],
                'http_errors' => false
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
                ],
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            return $result['data']['balance'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function processPayment()
    {
        $json = $this->request->getJSON();
        if (!$json) {
            return $this->fail('Invalid request.', 400);
        }

        $data = [
            "service_code"  => $json->service_code ?? '',
            "service_name"  => $json->service_name ?? '',
            "service_tariff" => $json->service_tariff ?? 0
        ];

        // Kirim request ke API eksternal
        $apiResponse = $this->sendToPaymentAPI($data);

        if (isset($apiResponse['status']) && $apiResponse['status'] == 0) {
            return $this->respond([
                'success' => true,
                'message' => $apiResponse['message'],
                'invoice_number' => $apiResponse['data']['invoice_number'] ?? null
            ]);
        } else {
            return $this->fail($apiResponse['message'] ?? 'Transaksi gagal.');
        }
    }

    private function sendToPaymentAPI($data)
    {
        $apiUrl = "https://take-home-test-api.nutech-integrasi.com/transaction";
        
        try {
            $response = $this->client->post($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'json' => $data,
                'http_errors' => false
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['status' => -1, 'message' => 'Gagal terhubung ke API'];
        }
    }

    public function payment($service_code)
{
    if (!$this->token) {
        return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $services = [
        "PBB" => ["PBB", "PBB.png", 100000],
        "Listrik" => ["Listrik Prabayar", "listrik.png", 50000],
        "Pulsa" => ["Pulsa", "pulsa.png", 20000],
        "PDAM" => ["PDAM", "PDAM.png", 75000],
        "PGN" => ["PGN Berlangganan", "PGN.png", 50000],
        "TV Langganan" => ["TV Langganan", "Televisi.png", 150000],
        "Musik" => ["Musik Streaming", "Musik.png", 25000],
        "Game" => ["Game Voucher", "Game.png", 50000],
        "Voucher Makanan" => ["Voucher Makanan", "Voucher Makanan.png", 100000],
        "Kurban" => ["Kurban", "Kurban.png", 500000],
        "Zakat" => ["Zakat", "Zakat.png", 100000],
        "Paket Data" => ["Paket Data", "Paket Data.png", 50000]
    ];

    if (!isset($services[$service_code])) {
        return redirect()->to('/')->with('error', 'Layanan tidak ditemukan.');
    }

    $service = $services[$service_code];

    $data = [
        'profile' => $this->fetchProfile(),
        'balance' => $this->fetchBalance(),
        'service_code' => $service_code,
        'service_name' => $service[0],
        'service_icon' => base_url('img/' . $service[1]),
        'service_tariff' => $service[2]
    ];

    return view('dashboard/pembayaran', $data);
}
    public function processTopUp()
    {
        helper(['form']);

        $rules = [
            'nominal' => 'required|numeric|greater_than[9999]' // Minimal Rp10.000
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Nominal tidak valid atau terlalu kecil.');
        }

        $nominal = (int) $this->request->getPost('nominal');

        if (!$this->token) {
            return redirect()->back()->with('error', 'Sesi Anda telah habis, silakan login kembali.');
        }

        try {
            $response = $this->client->post('https://take-home-test-api.nutech-integrasi.com/topup', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json'
                ],
                'json' => ['top_up_amount' => $nominal],
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result['status'] == 0) {
                session()->set('balance', $result['data']['balance'] ?? 0);
                return redirect()->back()->with('success', $result['message']);
            }

            return redirect()->back()->with('error', 'Gagal melakukan top-up: ' . ($result['message'] ?? 'Terjadi kesalahan.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghubungi server.');
        }
    }
    public function more()
    {
        if (!$this->token) {
            return $this->failUnauthorized('Silakan login terlebih dahulu.');
        }

        $page = (int) $this->request->getGet('page');
        $limit = 10;
        $offset = $page * $limit;

        $transactions = $this->fetchTransactions($limit, $offset);
        $hasMore = count($transactions) === $limit;

        return $this->respond([ 'transactions' => $transactions, 'hasMore' => $hasMore ]);
    }

    private function fetchTransactions($limit, $offset)
    {
        try {
            $response = $this->client->get('https://take-home-test-api.nutech-integrasi.com/transaction/history', [
                'headers' => ['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/json'],
                'http_errors' => false,
                'query' => ['limit' => $limit, 'offset' => $offset]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data']['records'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
    public function TopUp()
        {
            $token = session()->get('token');
             // Pastikan user sudah login
        if (!session()->get('token')) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
        }
            $data['profile'] = $this->fetchProfile();
            $data['balance'] = $this->fetchBalance();

            return view('dashboard/top-up', $data);
        }

   

}


