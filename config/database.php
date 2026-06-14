<?php

require_once __DIR__ . '/app.php';

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$db   = env('DB_NAME', 'glowe_ecommerce');
$user = env('DB_USER', 'root');
$pass = env('DB_PASS', '');
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}