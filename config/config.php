<?php
// require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../env_simple.php';

// EnvLoader::load();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// BASE_PATH: Path absolut ke root project di server
define('BASE_PATH', realpath(__DIR__ . '/../') . '/');

// BASE_URL: Path URL dinamis (misalnya http://localhost/magang/)
// $host = $_SERVER['HTTP_HOST']; // Contoh: localhost
// $documentRoot = realpath($_SERVER['DOCUMENT_ROOT']); // Contoh: C:/xampp/htdocs
// var_dump($documentRoot); die;
// $projectPath = realpath(__DIR__ . '/../'); // Path ke folder utama project

// Hitung base folder relatif terhadap document root
// $baseFolder = str_replace('\\', '/', str_replace($documentRoot, '', $projectPath));

// Gabungkan host dan folder untuk URL
// define('BASE_URL', "http://$host$baseFolder/");
define('BASE_URL', $BASE_URL);

// Konfigurasi database
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'magang_php');