<?php
class ConnectDB
{
    // Thêm ?ConnectDB: Nghĩa là biến này có thể là kiểu ConnectDB hoặc null
    private static ?ConnectDB $instance = null;

    // Thêm mysqli: Vì bạn dùng hàm mysqli_connect ở dưới
    private mysqli $conn;

    private string $host     = 'localhost';
    private string $dbname   = '68PM34';
    private string $username = 'root';
    private string $password = '';

    private function __construct()
    {
        $this->conn = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );

        if (!$this->conn) {
            die('Kết nối database thất bại: ' . mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, 'utf8mb4');
    }

    public static function getInstance(): ConnectDB
    {
        if (self::$instance === null) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }

    // Khai báo thêm hàm này trả về một đối tượng mysqli
    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}
