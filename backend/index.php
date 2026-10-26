<?php
// API entry point and request router.

declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/helpers/Auth.php';

require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Order.php';

require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/OrderController.php';

$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$scriptDirectory = str_replace(
    '\\',
    '/',
    dirname($_SERVER['SCRIPT_NAME'])
);

$scriptDirectory = rtrim(
    $scriptDirectory,
    '/'
);

$path = $uri;

if (
    $scriptDirectory !== '' &&
    $scriptDirectory !== '/' &&
    str_starts_with($path, $scriptDirectory)
) {
    $path = substr($path, strlen($scriptDirectory));
}

$path = trim($path, '/');

$scriptName = basename($_SERVER['SCRIPT_NAME']);

if ($path === $scriptName) {
    $path = '';
} elseif (str_starts_with($path, $scriptName . '/')) {
    $path = substr($path, strlen($scriptName) + 1);
}

$parts = $path === ''
    ? []
    : explode('/', $path);

$resource = strtolower($parts[0] ?? '');

$id = null;

if (
    isset($parts[1]) &&
    ctype_digit($parts[1])
) {
    $id = (int)$parts[1];
}

if ($resource === '') {
    Response::json(
        true,
        'E-commerce API is running.',
        [
            'products' => 'GET /products',
            'categories' => 'GET /categories',
            'register' => 'POST /register',
            'login' => 'POST /login',
            'logout' => 'POST /logout',
            'me' => 'GET /me',
            'cart' => 'GET/POST /cart',
            'orders' => 'GET/POST /orders'
        ]
    );
}

if ($resource === 'products') {
    ProductController::handle(
        $db->conn,
        $method,
        $id
    );
}

if ($resource === 'categories') {
    CategoryController::handle(
        $db->conn,
        $method,
        $id
    );
}

if ($resource === 'register' && $method === 'POST') {
    AuthController::register($db->conn);
}

if ($resource === 'login' && $method === 'POST') {
    AuthController::login($db->conn);
}

if ($resource === 'logout' && $method === 'POST') {
    AuthController::logout();
}

if ($resource === 'me' && $method === 'GET') {
    AuthController::me();
}

if ($resource === 'users' && $method === 'GET') {
    Auth::requireAdmin();

    $userModel = new User($db->conn);

    Response::json(
        true,
        'Users loaded.',
        $userModel->all()
    );
}

if ($resource === 'cart') {
    CartController::handle(
        $db->conn,
        $method,
        $id
    );
}

if ($resource === 'orders') {
    OrderController::handle(
        $db->conn,
        $method,
        $id
    );
}

Response::json(
    false,
    'Endpoint not found.',
    null,
    404
);


























