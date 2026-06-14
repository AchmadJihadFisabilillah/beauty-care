<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use PDO;

class CartService
{
    public static function count(?PDO $pdo = null, ?int $userId = null): int
    {
        if (!$pdo || !$userId) {
            return 0;
        }

        $cartModel = new Cart($pdo);
        $cart = $cartModel->findByUser($userId);
        if (!$cart) {
            return 0;
        }

        $cartItemModel = new CartItem($pdo);
        return $cartItemModel->countQty((int) $cart['id']);
    }

    public static function subtotal(?PDO $pdo = null, ?int $userId = null): float
    {
        if (!$pdo || !$userId) {
            return 0;
        }

        $cartModel = new Cart($pdo);
        $cart = $cartModel->findByUser($userId);
        if (!$cart) {
            return 0;
        }

        $cartItemModel = new CartItem($pdo);
        return $cartItemModel->subtotal((int) $cart['id']);
    }
}
