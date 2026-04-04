<?php
namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private User $users;

    public function __construct($pdo)
    {
        $this->users = new User($pdo);
    }

    public function attemptLogin(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        return true;
    }
}   