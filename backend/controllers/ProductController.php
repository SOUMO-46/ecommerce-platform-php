<?php
declare(strict_types=1);
// Handles product CRUD operations.

class ProductController
{
    public static function handle(
        mysqli $db,
        string $method,
        ?int $id
    ): void {
        $model = new Product($db);

        if ($method === 'GET') {
            $search = isset($_GET['search'])
                ? trim((string)$_GET['search'])
                : null;

            $categoryId = isset($_GET['category_id'])
                ? (int)$_GET['category_id']
                : null;

            if ($id !== null) {
                $product = $model->find($id);

                if (!$product) {
                    Response::json(
                        false,
                        'Product not found.',
                        null,
                        404
                    );
                }

                Response::json(
                    true,
                    'Product found.',
                    $product
                );
            }

            Response::json(
                true,
                'Products loaded.',
                $model->all($search, $categoryId)
            );
        }

        Auth::requireAdmin();

        $body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        if ($method === 'POST') {
            $name = trim((string)($body['name'] ?? ''));
            $price = (float)($body['price'] ?? 0);
            $stock = (int)($body['stock'] ?? 0);

            if ($name === '') {
                Response::json(
                    false,
                    'Product name is required.',
                    null,
                    422
                );
            }

            if ($price < 0 || $stock < 0) {
                Response::json(
                    false,
                    'Price and stock cannot be negative.',
                    null,
                    422
                );
            }

            $newId = $model->create($body);

            Response::json(
                true,
                'Product created.',
                ['id' => $newId],
                201
            );
        }

        if ($method === 'PUT' && $id !== null) {
            if (!$model->find($id)) {
                Response::json(
                    false,
                    'Product not found.',
                    null,
                    404
                );
            }

            $name = trim((string)($body['name'] ?? ''));
            $price = (float)($body['price'] ?? 0);
            $stock = (int)($body['stock'] ?? 0);

            if ($name === '' || $price < 0 || $stock < 0) {
                Response::json(
                    false,
                    'Valid name, price and stock are required.',
                    null,
                    422
                );
            }

            $model->update($id, $body);

            Response::json(
                true,
                'Product updated.'
            );
        }

        if ($method === 'DELETE' && $id !== null) {
            if (!$model->find($id)) {
                Response::json(
                    false,
                    'Product not found.',
                    null,
                    404
                );
            }

            $model->delete($id);

            Response::json(
                true,
                'Product deleted.'
            );
        }

        Response::json(
            false,
            'Invalid product request.',
            null,
            400
        );
    }
}
















































