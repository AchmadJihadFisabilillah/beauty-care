<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderItem;

class OrderController
{
    private Order $orders;
    private OrderItem $items;

    public function __construct($pdo)
    {
        $this->orders = new Order($pdo);
        $this->items = new OrderItem($pdo);
    }

    public function detail(int $id): ?array
    {
        return $this->orders->detail($id);
    }

    public function items(int $id): array
    {
        return $this->items->byOrder($id);
    }
}