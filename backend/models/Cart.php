<?php
declare(strict_types=1);

class Cart
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    private function getCartId(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM cart WHERE user_id=?'
        );

        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return (int)$row['id'];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO cart (user_id) VALUES (?)'
        );

        $stmt->bind_param('i', $userId);
        $stmt->execute();

        return $stmt->insert_id;
    }




    public function items(int $userId): array
    {
        $cartId = $this->getCartId($userId);

        $stmt = $this->db->prepare(
            'SELECT ci.id, ci.product_id, ci.quantity,
                    p.name, p.price, p.stock, p.image,
                    (p.price * ci.quantity) AS subtotal
             FROM cart_items ci
             JOIN products p ON p.id=ci.product_id
             WHERE ci.cart_id=?
             ORDER BY ci.id DESC'
        );

        $stmt->bind_param('i', $cartId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function add(
        int $userId,
        int $productId,
        int $quantity
    ): bool {
        $cartId = $this->getCartId($userId);

        $stmt = $this->db->prepare(
            'INSERT INTO cart_items (cart_id,product_id,quantity)
             VALUES (?,?,?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );

        $stmt->bind_param(
            'iii',
            $cartId,
            $productId,
            $quantity
        );

        $stmt->execute();

        return true;
    }

    public function update(
        int $userId,
        int $itemId,
        int $quantity
    ): bool {
        $cartId = $this->getCartId($userId);

        $stmt = $this->db->prepare(
            'UPDATE cart_items
             SET quantity=?
             WHERE id=? AND cart_id=?'
        );

        $stmt->bind_param(
            'iii',
            $quantity,
            $itemId,
            $cartId
        );

        $stmt->execute();

        return true;
    }

    public function remove(int $userId, int $itemId): bool
    {
        $cartId = $this->getCartId($userId);

        $stmt = $this->db->prepare(
            'DELETE FROM cart_items
             WHERE id=? AND cart_id=?'
        );

        $stmt->bind_param('ii', $itemId, $cartId);
        $stmt->execute();

        return true;
    }
}











































