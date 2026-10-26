<?php
declare(strict_types=1);
// Handles category CRUD operations.

class CategoryController
{
    public static function handle(
        mysqli $db,
        string $method,
        ?int $id
    ): void {
        $model = new Category($db);

        if ($method === 'GET') {
            if ($id !== null) {
                $category = $model->find($id);

                if (!$category) {
                    Response::json(
                        false,
                        'Category not found.',
                        null,
                        404
                    );
                }

                Response::json(
                    true,
                    'Category found.',
                    $category
                );
            }

            Response::json(
                true,
                'Categories loaded.',
                $model->all()
            );
        }

        Auth::requireAdmin();

        $body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        $name = trim((string)($body['name'] ?? ''));


        if ($method === 'POST') {
            $newId = $model->create($name);

            Response::json(
                true,
                'Category created.',
                ['id' => $newId],
                201
            );
        }

        if ($method === 'PUT' && $id !== null) {
            if (!$model->find($id)) {
                Response::json(
                    false,
                    'Category not found.',
                    null,
                    404
                );
            }

            $model->update($id, $name);

            Response::json(
                true,
                'Category updated.'
            );
        }

        if ($method === 'DELETE' && $id !== null) {
            if (!$model->find($id)) {
                Response::json(
                    false,
                    'Category not found.',
                    null,
                    404
                );
            }

            $model->delete($id);

            Response::json(
                true,
                'Category deleted.'
            );
        }

        Response::json(
            false,
            'Invalid category request.',
            null,
            400
        );
    }
}


























