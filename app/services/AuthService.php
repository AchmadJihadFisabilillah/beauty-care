<?php
namespace App\Services;

class AuthService
{
    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }
}