<?php
class ConnectDB
{
    // 1. Thêm ?ConnectDB: Thuộc tính tĩnh lưu chính class này hoặc mang giá trị null
    private static ?ConnectDB $instance = null;

    // 2. Thêm \PDO: Vì thuộc tính này bây giờ là một đối tượng kết nối PDO
    private \PDO $conn;

    private function __construct()
    {
        $host   = 'localhost';
        $dbname = '68PM34';
        $user   = 'root';
        $pass   = '';

        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user, $pass // NOSONAR
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Kết nối thất bại: ' . $e->getMessage());
        }
    }

    // 3. Khai báo hàm này bắt buộc phải trả về một thực thể ConnectDB
    public static function getInstance(): ConnectDB
    {
        if (self::$instance === null) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }

    // 4. Khai báo hàm này trả về đối tượng kết nối \PDO
    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}
