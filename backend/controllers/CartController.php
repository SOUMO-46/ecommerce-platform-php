<?php
declare(strict_types=1);
// Handles authenticated cart operations.

class CartController
{
    public static function handle(
        mysqli $db,
        string $method,
        ?int $id
    ): void {
        $user = Auth::requireLogin();
        $model = new Cart($db);

        $body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        $userId = (int)$user['id'];

        if ($method === 'GET') {
            Response::json(
                true,
                'Cart loaded.',
                $model->items($userId)
            );
        }



        if ($method === 'POST') {
            $productId = (int)($body['product_id'] ?? 0);
            $quantity = (int)($body['quantity'] ?? 1);

            if ($productId <= 0 || $quantity <= 0) {
                Response::json(
                    false,
                    'Valid product_id and quantity are required.',
                    null,
                    422
                );
            }

            $productModel = new Product($db);

            if (!$productModel->find($productId)) {
                Response::json(
                    false,
                    'Product not found.',
                    null,
                    404
                );
            }

            $model->add(
                $userId,
                $productId,
                $quantity
            );

            Response::json(
                true,
                'Product added to cart.'
            );
        }

        if ($method === 'PUT' && $id !== null) {
            $quantity = (int)($body['quantity'] ?? 0);

            if ($quantity <= 0) {
                Response::json(
                    false,
                    'Quantity must be greater than zero.',
                    null,
                    422
                );
            }

            $model->update(
                $userId,
                $id,
                $quantity
            );

            Response::json(
                true,
                'Cart updated.'
            );
        }

        if ($method === 'DELETE' && $id !== null) {
            $model->remove($userId, $id);

            Response::json(
                true,
                'Cart item removed.'
            );
        }

        Response::json(
            false,
            'Invalid cart request.',
            null,
            400
        );
    }
}





































