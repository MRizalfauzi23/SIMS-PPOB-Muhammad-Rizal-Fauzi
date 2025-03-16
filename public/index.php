<?php

// Pastikan environment sudah diset
define('ENVIRONMENT', $_SERVER['CI_ENVIRONMENT'] ?? 'production');

// Path ke public
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load Paths Config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Pastikan autoloader CodeIgniter ada
require FCPATH . '../vendor/autoload.php';

// Load framework bootstrap
require rtrim($paths->systemDirectory, '/ ') . '/Boot.php';

// Pastikan server port sudah benar untuk Railway
$_SERVER['SERVER_PORT'] = getenv('PORT') ?: 8080;

// Jalankan aplikasi dengan instance, bukan statis
$app = new CodeIgniter\CodeIgniter($paths);
$app->run();
