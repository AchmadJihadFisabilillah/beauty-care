<?php

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/env.php';

env_load(BASE_PATH . '/.env');

define('APP_NAME', env('APP_NAME', 'Glowé'));
define('BASE_URL', env('APP_URL', '/glowe-ecommerce/public'));
define('UPLOAD_PRODUCT_DIR', BASE_PATH . '/public/uploads/products/');
define('UPLOAD_PAYMENT_DIR', BASE_PATH . '/public/uploads/payments/');
define('WHATSAPP_NUMBER', env('WHATSAPP_NUMBER', '6281234567890'));
define('SESSION_TIMEOUT', (int) env('SESSION_TIMEOUT', 7200));