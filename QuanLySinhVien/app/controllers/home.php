<?php
class Home
{
    public function index()
    {
        Middleware::protect();

        $user = Middleware::getUser();
        echo '<!DOCTYPE html><html lang="vi"><head>';
        echo '<meta charset="UTF-8">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">';
        echo '<title>Trang chủ</title></head><body>';
        echo '<div class="container mt-5">';
        echo '<h1>Xin chào, ' . htmlspecialchars($user['name']) . '!</h1>';
        echo '<p>Bạn đã đăng nhập thành công vào hệ thống Quản lý Sinh Viên.</p>';
        echo '<a href="' . BASE_URL . '/authen/logout" class="btn btn-danger">Đăng xuất</a>';
        echo '</div></body></html>';
    }
}
