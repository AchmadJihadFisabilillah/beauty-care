<?php
require_once BASE_PATH . '/app/helpers/functions.php';
require_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Panel') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <h2>Glowé Admin</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
        <a href="<?= BASE_URL ?>/admin/products">Products</a>
        <a href="<?= BASE_URL ?>/admin/brands">Brands</a>
        <a href="<?= BASE_URL ?>/admin/categories">Categories</a>
        <a href="<?= BASE_URL ?>/admin/orders">Orders</a>
        <a href="<?= BASE_URL ?>/admin/payments">Payments</a>
        <a href="<?= BASE_URL ?>/admin/shipping">Shipping</a>
        <a href="<?= BASE_URL ?>/admin/reports">Reports</a>
        <a href="<?= BASE_URL ?>/admin/logs">Logs</a>
        <a href="<?= BASE_URL ?>/logout">Logout</a>
    </aside>
    <section class="admin-content">