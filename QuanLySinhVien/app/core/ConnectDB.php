<?php
class ConnectDB
{
    private static $instance = null;

    private $conn;

    private $host     = 'localhost';
    private $dbname   = '68PM34';
    private $username = 'root';
    private $password = '';

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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
?>
