<?php
declare(strict_types=1);
// Handles registration, login, logout, and current-user requests.

class AuthController
{
    public static function register(mysqli $db): void
    {
        $body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        $name = trim((string)($body['name'] ?? ''));
        $email = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');

        if (
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 6
        ) {
            Response::json(
                false,
                'Name, valid email and password (6+ characters) are required.',
                null,
                422
            );
        }

        $model = new User($db);

        if ($model->findByEmail($email)) {
            Response::json(
                false,
                'Email already registered.',
                null,
                409
            );
        }

        $id = $model->create(
            $name,
            $email,
            $password
        );

        Response::json(
            true,
            'Registration successful.',
            ['id' => $id],
            201
        );
    }

    

    public static function login(mysqli $db): void
    {
        $body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];

        $email = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');

        $model = new User($db);
        $user = $model->findByEmail($email);

        if (
            !$user ||
            !password_verify($password, $user['password'])
        ) {
            Response::json(
                false,
                'Invalid email or password.',
                null,
                401
            );
        }

        Auth::start();
        session_regenerate_id(true);

        unset($user['password']);

        $_SESSION['user'] = $user;

        Response::json(
            true,
            'Login successful.',
            $user
        );
    }



    public static function logout(): void
    {
        Auth::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        Response::json(
            true,
            'Logged out.'
        );
    }



    public static function me(): void
    {
        $user = Auth::user();

        Response::json(
            true,
            'Current user.',
            $user
        );
    }
}



























































