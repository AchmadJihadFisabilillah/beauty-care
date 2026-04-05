<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use PDO;

class CartController
{
    public function add(PDO $pdo, array $product, int $qty): void
    {
        if (empty($_SESSION['user']['id'])) {
            flash('error', 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.');
            redirect('/login');
        }

        $cartModel = new Cart($pdo);
        $cartItemModel = new CartItem($pdo);
        $cart = $cartModel->getOrCreate((int) $_SESSION['user']['id']);

        $qty = max(1, min($qty, (int) $product['stock']));
        $cartItemModel->upsert((int) $cart['id'], $product, $qty);
    }
}
