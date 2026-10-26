<?php
declare(strict_types=1);

class Category
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $result = $this->db->query(
            'SELECT * FROM categories ORDER BY id DESC'
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM categories WHERE id=?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row ?: null;
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name) VALUES (?)'
        );

        $stmt->bind_param('s', $name);
        $stmt->execute();

        return $stmt->insert_id;
    }


    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name=? WHERE id=?'
        );

        $stmt->bind_param('si', $name, $id);
        $stmt->execute();

        return true;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM categories WHERE id=?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        return true;
    }
}











