<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\TransactionController;


/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/', 'AuthController::loginForm');
$routes->get('register', 'AuthController::registerForm');
$routes->post('auth/register', 'AuthController::register');
$routes->post('auth/login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Dashboard Routes (Hanya bisa diakses jika login)
$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('fetch-banners', 'DashboardController::fetchAndStoreBanners');
    $routes->get('import-json', 'ImportController::importJson');
});

// Profile Routes
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ProfileController::profile');
    $routes->post('uploadImage', 'ProfileController::uploadImage');
    $routes->post('updateProfile', 'ProfileController::updateProfile');
});

// Transaction Routes
$routes->group('transaction', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TransactionController::transaction');
    $routes->get('/transaction/topup', 'TransactionController::TopUp');
    $routes->post('process', 'TransactionController::processTopUp');
    $routes->get('more', 'TransactionController::more');
    $routes->get('payment/(:segment)', 'TransactionController::payment/$1'); 
    $routes->post('payment', 'TransactionController::processPayment');
});
$routes->get('/transaction/topup', 'TransactionController::TopUp');
