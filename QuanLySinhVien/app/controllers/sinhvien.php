<?php
require_once '../app/core/Controller.php';

class Sinhvien extends Controller
{
    // Ép kiểu trả về là void cho hàm index
    public function index(): void
    {
        Middleware::protect();

        $sinhvienModel = $this->model('sinhvienModel');
        $sinhviens     = $sinhvienModel->getAllSinhvien();

        $this->view("layout/masterlayout", [
            'viewname'  => 'sinhvien/index',
            'sinhviens' => $sinhviens
        ]);
    }

    // Ép kiểu trả về là void cho hàm create
    public function create(): void
    {
        Middleware::protect();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten    = trim($_POST['hoten']    ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv     = trim($_POST['mssv']     ?? '');

            if (empty($hoten) || empty($gioitinh) || empty($mssv)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
            } else {
                $model = $this->model('sinhvienModel');
                if ($model->create($hoten, $gioitinh, $mssv)) {
                    header('Location: ' . BASE_URL . '/sinhvien/index');
                    exit();
                } else {
                    $error = 'Thêm thất bại, MSSV có thể đã tồn tại!';
                }
            }
        }

        $this->view("layout/masterlayout", [
            'viewname' => 'sinhvien/themsv',
            'error'    => $error
        ]);
    }
}