<?php
require_once __DIR__ . '/../config.php'; // nạp config trước

class ConnectDB
{
    // Đã thêm kiểu dữ liệu: ?self (có thể là chính nó hoặc null)
    private static ?self $instance = null;
    
    // Đã thêm kiểu dữ liệu: PDO
    private PDO $conn;

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Kết nối thất bại: ' . $e->getMessage());
        }
    }

    // Tiện tay thêm luôn kiểu dữ liệu trả về cho hàm (return type hint) để code chuẩn chỉnh hơn
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }

    // Thêm kiểu trả về là PDO cho hàm này
    public function getConnection(): PDO {
        return $this->conn;
    }
}
