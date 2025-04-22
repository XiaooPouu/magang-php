<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../vendor/autoload.php'; //autoload composer

use Medoo\Medoo;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Database {
    private $db;

    public function __construct() {
        $this->db = new Medoo([
            'type' => 'mysql',
            'host' => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'utf8mb4' // opsional tapi direkomendasikan
        ]);
    }

    public function getConnection() {
        return $this->db;
    }
}