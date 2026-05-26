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

        $danhSach = $this->model->getAll();

        require_once '../app/views/sinhvien/index.php';
    }
}
