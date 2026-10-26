<?php
declare(strict_types=1);
// Handles order creation and order retrieval.

class OrderController
{
    public static function handle(
        mysqli $db,
        string $method,
        ?int $id
    ): void {
        $user = Auth::requireLogin();
        $model = new Order($db);

        $userId = (int)$user['id'];

        if ($method === 'GET') {
            if (($user['role'] ?? '') === 'admin') {
                Response::json(
                    true,
                    'Orders loaded.',
                    $model->all()
                );
            }

            Response::json(
                true,
                'Orders loaded.',
                $model->userOrders($userId)
            );
        }

        if ($method === 'POST') {
            try {
                $orderId = $model->createFromCart($userId);

                Response::json(
                    true,
                    'Order created.',
                    ['id' => $orderId],
                    201
                );
            } catch (Throwable $e) {
                Response::json(
                    false,
                    $e->getMessage(),
                    null,
                    422
                );
            }
        }

        if ($method === 'PUT' && $id !== null) {
            Auth::requireAdmin();

            $body = json_decode(
                file_get_contents('php://input'),
                true
            ) ?? [];

            $status = (string)($body['status'] ?? '');

            if (!$model->updateStatus($id, $status)) {
                Response::json(
                    false,
                    'Invalid order status.',
                    null,
                    422
                );
            }

            Response::json(
                true,
                'Order status updated.'
            );
        }

        Response::json(
            false,
            'Invalid order request.',
            null,
            400
        );
    }
}









