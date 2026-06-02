<?php

class Authen
{
    private const REDIRECT    = 'Location: ';
    private const LOGIN_ROUTE = '/authen/login';
    private const HOME_ROUTE  = '/home/index';

    public function login()
    {
        Middleware::guest();

        $error = $_SESSION['login_error'] ?? ''; // NOSONAR
        unset($_SESSION['login_error']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {

                $_SESSION['login_error'] = 'Vui lòng nhập đầy đủ thông tin!';

                header(self::REDIRECT . BASE_URL . self::LOGIN_ROUTE);
                exit();

            } elseif ($username === 'admin' && $password === '123456') {

                $_SESSION['user'] = [
                    'username' => $username,
                    'role'     => 'admin',
                    'name'     => 'Quản trị viên'
                ];

                header(self::REDIRECT . BASE_URL . self::HOME_ROUTE);
                exit();

            } else {

                $_SESSION['login_error'] = 'Sai tên đăng nhập hoặc mật khẩu!';

                header(self::REDIRECT . BASE_URL . self::LOGIN_ROUTE);
                exit();
            }
        }
        

        require_once '../app/views/home/login.php';
    }

    public function logout()
    {
        session_destroy();

        header(self::REDIRECT . BASE_URL . self::LOGIN_ROUTE);
        exit();
    }

    public function register()
    {
        Middleware::guest();

        echo '<h2>Trang đăng ký — sẽ phát triển sau</h2>';
    }
}
