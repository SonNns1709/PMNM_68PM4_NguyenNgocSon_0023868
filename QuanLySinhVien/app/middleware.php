<?php
class Middleware
{
    public static function protect()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/authen/login');
            exit();
        }
    }

    public static function guest()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/home/index');
            exit();
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    public static function getUser()
    {
        return $_SESSION['user'] ?? null;
    }
}

define('BASE_URL', '/PMNM_68PM4_NguyenNgocSon_0023868/QuanLySinhVien/public');

