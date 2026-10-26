<?php
declare(strict_types=1);

class Product
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function all(?string $search = null, ?int $categoryId = null): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE 1=1";

        $types = '';
        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' AND p.name LIKE ?';
            $types .= 's';
            $params[] = '%' . $search . '%';
        }

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= ' AND p.category_id = ?';
            $types .= 'i';
            $params[] = $categoryId;
        }

        $sql .= ' ORDER BY p.id DESC';

        $stmt = $this->db->prepare($sql);

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row ?: null;
    }

    public function create(array $data): int
    {
        $categoryId = isset($data['category_id']) && $data['category_id'] !== ''
            ? (int)$data['category_id']
            : null;

        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $price = (float)($data['price'] ?? 0);
        $stock = (int)($data['stock'] ?? 0);
        $image = !empty($data['image']) ? trim((string)$data['image']) : null;

        $stmt = $this->db->prepare(
            'INSERT INTO products
             (category_id, name, description, price, stock, image)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'issdis',
            $categoryId,
            $name,
            $description,
            $price,
            $stock,
            $image
        );

        $stmt->execute();

        return $stmt->insert_id;
    }

    public function update(int $id, array $data): bool
    {
        $categoryId = isset($data['category_id']) && $data['category_id'] !== ''
            ? (int)$data['category_id']
            : null;

        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $price = (float)($data['price'] ?? 0);
        $stock = (int)($data['stock'] ?? 0);
        $image = !empty($data['image']) ? trim((string)$data['image']) : null;

        $stmt = $this->db->prepare(
            'UPDATE products
             SET category_id=?, name=?, description=?, price=?, stock=?, image=?
             WHERE id=?'
        );

        $stmt->bind_param(
            'issdisi',
            $categoryId,
            $name,
            $description,
            $price,
            $stock,
            $image,
            $id
        );

        $stmt->execute();

        return true;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return true;
    }
}










