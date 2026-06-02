<?php
require_once __DIR__ . '/ConnectDB.php';

class Model
{
    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = ConnectDB::getInstance()->getConnection();
    }
}
