<?php
namespace App\Models;

class User extends BaseModel
{
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?,?)'
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['password'],
            $data['role'] ?? 'user',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function allUsers(): array
    {
        return $this->db->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll();
    }

    public function saveResetToken(string $email, string $tokenHash, string $expiresAt): bool
{
    $stmt = $this->db->prepare(
        'UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE email = ? LIMIT 1'
    );

    return $stmt->execute([$tokenHash, $expiresAt, $email]);
}

    public function findByValidResetToken(string $token): ?array
{
    $tokenHash = hash('sha256', $token);

    $stmt = $this->db->prepare(
        'SELECT * FROM users WHERE reset_token = ? AND reset_expires_at >= NOW() LIMIT 1'
    );

    $stmt->execute([$tokenHash]);

    return $stmt->fetch() ?: null;
}

    public function updatePassword(int $id, string $hashedPassword): bool
{
    $stmt = $this->db->prepare(
        'UPDATE users SET password = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ? LIMIT 1'
    );

    return $stmt->execute([$hashedPassword, $id]);
}

}