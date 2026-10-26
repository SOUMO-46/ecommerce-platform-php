<?php
declare(strict_types=1);

class Order

{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function createFromCart(int $userId): int
    {
        $this->db->begin_transaction();

        try {
            $stmt = $this->db->prepare(
                'SELECT ci.product_id, ci.quantity,
                        p.name, p.price, p.stock
                 FROM cart c
                 JOIN cart_items ci ON ci.cart_id=c.id
                 JOIN products p ON p.id=ci.product_id
                 WHERE c.user_id=?
                 FOR UPDATE'
            );

            $stmt->bind_param('i', $userId);
            $stmt->execute();

            $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if (!$items) {
                throw new Exception('Cart is empty.');
            }

            $total = 0.0;

            foreach ($items as $item) {
                if ((int)$item['quantity'] > (int)$item['stock']) {
                    throw new Exception(
                        'Insufficient stock for ' . $item['name']
                    );
                }

                $total +=
                    (float)$item['price'] *
                    (int)$item['quantity'];
            }

            $stmt = $this->db->prepare(
                'INSERT INTO orders (user_id,total_amount)
                 VALUES (?,?)'
            );

            $stmt->bind_param('id', $userId, $total);
            $stmt->execute();

            $orderId = $stmt->insert_id;

            $itemStmt = $this->db->prepare(
                'INSERT INTO order_items
                 (order_id,product_id,product_name,price,quantity)
                 VALUES (?,?,?,?,?)'
            );

            $stockStmt = $this->db->prepare(
                'UPDATE products
                 SET stock=stock-?
                 WHERE id=?'
            );

            foreach ($items as $item) {
                $productId = (int)$item['product_id'];
                $name = (string)$item['name'];
                $price = (float)$item['price'];
                $quantity = (int)$item['quantity'];

                $itemStmt->bind_param(
                    'iisdi',
                    $orderId,
                    $productId,
                    $name,
                    $price,
                    $quantity
                );

                $itemStmt->execute();

                $stockStmt->bind_param(
                    'ii',
                    $quantity,
                    $productId
                );

                $stockStmt->execute();
            }

            $stmt = $this->db->prepare(
                'DELETE ci
                 FROM cart_items ci
                 JOIN cart c ON c.id=ci.cart_id
                 WHERE c.user_id=?'
            );

            $stmt->bind_param('i', $userId);
            $stmt->execute();

            $this->db->commit();

            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function userOrders(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders
             WHERE user_id=?
             ORDER BY id DESC'
        );

        $stmt->bind_param('i', $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function all(): array
    {
        $result = $this->db->query(
            'SELECT o.*, u.name AS customer_name, u.email
             FROM orders o
             JOIN users u ON u.id=o.user_id
             ORDER BY o.id DESC'
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus(
        int $id,
        string $status
    ): bool {
        $allowed = [
            'pending',
            'confirmed',
            'shipped',
            'delivered',
            'cancelled'
        ];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            'UPDATE orders SET status=? WHERE id=?'
        );

        $stmt->bind_param('si', $status, $id);
        $stmt->execute();

        return true;
    }
}















