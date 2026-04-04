<?php
namespace App\Models;

use PDO;

class BaseModel
{
    protected PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }
}