<?php
namespace App\Services;

class CartService
{
    public static function count(): int
    {
        if (empty($_SESSION['cart'])) {
            return 0;
        }

        return array_sum(array_column($_SESSION['cart'], 'qty'));
    }

    public static function subtotal(): float
    {
        $subtotal = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        return $subtotal;
    }
}