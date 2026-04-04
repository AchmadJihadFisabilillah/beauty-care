<?php require_once BASE_PATH . '/app/helpers/functions.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap nav-wrap-main">
        <a class="brand" href="<?= BASE_URL ?>/">Glowé</a>

        <form class="global-search" method="get" action="<?= BASE_URL ?>/catalog">
            <input type="text" name="q" placeholder="Cari produk, brand, kategori..." value="<?= e($_GET['q'] ?? '') ?>">
            <button class="btn btn-sm">Cari</button>
        </form>

        <nav class="nav-links">
            <a href="<?= BASE_URL ?>/catalog">Catalog</a>
            <a href="<?= BASE_URL ?>/cart">Cart (<?= cart_count() ?>)</a>
            <?php if (is_logged_in()): ?>
                <a href="<?= BASE_URL ?>/user/dashboard">Dashboard</a>
                <?php if (is_admin()): ?><a href="<?= BASE_URL ?>/admin/dashboard">Admin</a><?php endif; ?>
                <a href="<?= BASE_URL ?>/logout">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login">Login</a>
                <a href="<?= BASE_URL ?>/register">Register</a>
            <?php endif; ?>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank">WhatsApp</a>
        </nav>
    </div>
</header>
<main class="container page-space">
    <?php if ($msg = flash('success')): ?>
        <div class="alert success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="alert error"><?= e($msg) ?></div>
    <?php endif; ?>