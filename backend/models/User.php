<?php
declare(strict_types=1);

class User
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function create(
        string $name,
        string $email,
        string $password,
        string $role = 'customer'
    ): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            'INSERT INTO users (name,email,password,role)
             VALUES (?,?,?,?)'
        );

        $stmt->bind_param('ssss', $name, $email, $hash, $role);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email=?'
        );

        $stmt->bind_param('s', $email);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row ?: null;
    }

    public function all(): array
    {
        $result = $this->db->query(
            'SELECT id,name,email,role,created_at
             FROM users ORDER BY id DESC'
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}















