<?php
declare(strict_types=1);
// Session-based authentication and authorization helpers.

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function requireLogin(): array
    {
        $user = self::user();

        if (!$user) {
            Response::json(false, 'Login required.', null, 401);
        }

        return $user;
    }

    public static function requireAdmin(): array
    {
        $user = self::requireLogin();

        if (($user['role'] ?? '') !== 'admin') {
            Response::json(false, 'Admin access required.', null, 403);
        }

        return $user;
    }
}







