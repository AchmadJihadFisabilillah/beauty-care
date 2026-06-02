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

    public function createPasswordResetToken(string $email): ?string
    {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        return $this->users->saveResetToken($email, $tokenHash, $expiresAt) ? $token : null;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $user = $this->users->findByValidResetToken($token);
        if (!$user) {
            return false;
        }

        return $this->users->updatePassword(
            (int) $user['id'],
            password_hash($newPassword, PASSWORD_DEFAULT)
        );
    }
}