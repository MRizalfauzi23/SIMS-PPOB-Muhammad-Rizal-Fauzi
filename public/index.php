<?php

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */
define('ENVIRONMENT', $_SERVER['CI_ENVIRONMENT'] ?? 'production');

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// LOAD OUR PATHS CONFIG FILE
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require rtrim($paths->systemDirectory, '/ ') . '/Boot.php';

$port = getenv('PORT') ?: 8080; // Ensure the port is set correctly for Railway
$_SERVER['SERVER_PORT'] = $port;

exit(CodeIgniter\CodeIgniter::run());
