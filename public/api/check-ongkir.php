<?php

require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/app/helpers/functions.php';
require_once dirname(__DIR__, 2) . '/config/ongkir.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_login();

$apiKey = ONGKIR_API_KEY;
$origin = ONGKIR_ORIGIN;

$addressId = (int) ($_POST['address_id'] ?? 0);
$courier = trim($_POST['courier'] ?? '');

if ($addressId <= 0 || $courier === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Alamat dan kurir wajib dipilih.'
    ]);
    exit;
}

$addressStmt = $pdo->prepare("
    SELECT destination_id
    FROM addresses
    WHERE id = ? AND user_id = ?
    LIMIT 1
");
$addressStmt->execute([$addressId, current_user_id()]);
$address = $addressStmt->fetch();

if (!$address || empty($address['destination_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Destination ID alamat belum diisi.'
    ]);
    exit;
}

$cartStmt = $pdo->prepare("
    SELECT 
        ci.qty,
        p.weight
    FROM carts c
    JOIN cart_items ci ON ci.cart_id = c.id
    JOIN products p ON p.id = ci.product_id
    WHERE c.user_id = ?
");
$cartStmt->execute([current_user_id()]);
$cartItems = $cartStmt->fetchAll();

$totalWeight = 0;

foreach ($cartItems as $item) {
    $weight = (int) ($item['weight'] ?? 100);
    $qty = (int) ($item['qty'] ?? 1);

    $totalWeight += max($weight, 100) * $qty;
}

if ($totalWeight <= 0) {
    $totalWeight = 1000;
}

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'origin' => $origin,
        'destination' => $address['destination_id'],
        'weight' => $totalWeight,
        'courier' => $courier,
    ]),
    CURLOPT_HTTPHEADER => [
        'key: ' . $apiKey,
        'Content-Type: application/x-www-form-urlencoded'
    ],
]);

$response = curl_exec($curl);
$error = curl_error($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($error) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghubungi API ongkir: ' . $error
    ]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        'success' => false,
        'message' => 'API ongkir gagal.',
        'debug' => $data
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'weight' => $totalWeight,
    'data' => $data['data'] ?? []
]);