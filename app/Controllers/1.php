<?php
namespace App\Controllers;

class CartController
{
    public function add(array $product, int $qty): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $id = (int) $product['id'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
            return;
        }

        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $product['name'],
            'price' => (float) $product['price'],
            'qty' => $qty,
            'stock' => (int) $product['stock'],
            'image' => $product['image'] ?? null,
        ];
    }
}