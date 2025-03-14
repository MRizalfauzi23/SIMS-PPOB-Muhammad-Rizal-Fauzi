<?php

namespace App\Controllers;

use App\Models\BannerModel;

class ImportController extends BaseController
{
    public function importJson()
    {
        $filePath = FCPATH . 'uploads/banners.json';


        if (!file_exists($filePath)) {
            return "File JSON tidak ditemukan!";
        }

        // Baca file JSON
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);

        if (!$data || !isset($data['data'])) {
            return "Format JSON tidak valid!";
        }

        $bannerModel = new BannerModel();

        foreach ($data['data'] as $banner) {
            // Cek apakah banner sudah ada
            $existing = $bannerModel->where('banner_name', $banner['banner_name'])->first();

            if (!$existing) {
                $bannerModel->insert([
                    'banner_name'   => $banner['banner_name'],
                    'banner_image'  => $banner['banner_image'],
                    'description'   => $banner['description']
                ]);
            }
        }

        return "Import JSON berhasil!";
    }
}
