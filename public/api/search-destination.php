<?php

$apiKey = '0WpTrYBZ54b81187fa84f803PJchfrk3';

$search = $_GET['search'] ?? 'telang bangkalan';

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . urlencode($search),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'key: ' . $apiKey
    ],
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    die(curl_error($curl));
}

curl_close($curl);

echo '<pre>';
print_r(json_decode($response, true));
echo '</pre>';