<?php
require_once '../app/models/sinhvienModel.php';

class Sinhvien
{
    // 1. Sửa lỗi Intelephense: Khai báo kiểu dữ liệu cho thuộc tính $model
    private sinhvienModel $model;

    public function __construct()
    {
        $this->model = new sinhvienModel();
    }

    public function index()
    {
        Middleware::protect();

        // 2. Giải thích về lỗi SonarQube ($danhSach):
        // Bạn tạo biến $danhSach ở đây, nhưng file index.php bên dưới có thể đang dùng một tên biến khác,
        // hoặc bạn quên chưa gọi biến này trong file view đó.
        $danhSach = $this->model->getAll();

        require_once '../app/views/sinhvien/index.php';
    }
}
