<?php
require_once BASE_PATH . '/app/helpers/functions.php';
require_admin();

$currentUrl = $_GET['url'] ?? '/';
function isActive($path) {
    global $currentUrl;
    if ($path === '/admin/dashboard') {
        return ($currentUrl === '/admin/dashboard') ? 'active' : '';
    }
    return (strpos($currentUrl, $path) === 0) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Panel') ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <h2>Beauty Care</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= isActive('/admin/dashboard') ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/products" class="<?= isActive('/admin/products') ?>">
            <i class="fa-solid fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/brands" class="<?= isActive('/admin/brands') ?>">
            <i class="fa-solid fa-tag"></i>
            <span>Brands</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/categories" class="<?= isActive('/admin/categories') ?>">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categories</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/orders" class="<?= isActive('/admin/orders') ?> <?= isActive('/admin/order-detail') ?>">
            <i class="fa-solid fa-shopping-bag"></i>
            <span>Orders</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/payments" class="<?= isActive('/admin/payments') ?>">
            <i class="fa-solid fa-credit-card"></i>
            <span>Payments</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/shipping" class="<?= isActive('/admin/shipping') ?>">
            <i class="fa-solid fa-truck"></i>
            <span>Shipping</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/reports" class="<?= isActive('/admin/reports') ?> <?= isActive('/admin/reports-export') ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Reports</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/logs" class="<?= isActive('/admin/logs') ?>">
            <i class="fa-solid fa-history"></i>
            <span>Logs</span>
        </a>
        <a href="<?= BASE_URL ?>/logout" class="logout-link">
            <i class="fa-solid fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </aside>
    <section class="admin-content">