<?php
namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    private Product $products;

    public function __construct($pdo)
    {
        $this->products = new Product($pdo);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->products->findBySlug($slug);
    }
}