<?php
declare(strict_types=1);
// Shared MySQLi database connection.

class Database
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'ecommerce';

    public mysqli $conn;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            $this->conn->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            header('Content-Type: application/json; charset=UTF-8');

            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed. Check XAMPP MySQL and database name.',
                'data' => null
            ]);
            exit;
        }
    }
}
