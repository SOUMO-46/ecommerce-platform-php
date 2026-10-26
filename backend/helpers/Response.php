<?php
declare(strict_types=1);
// Standard JSON response helper.

class Response
{
    public static function json(bool $success,string $message = '',mixed $data = null,int $status = 200): never {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}
