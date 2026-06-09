<?php
require_once '../app/core/Controller.php';

class Sinhvien extends Controller
{
       public function index()
    {
        Middleware::protect();

        $limit  = 5;
        $page   = isset($_GET['page'])   ? (int)   $_GET['page']   : 1;
        $search = isset($_GET['search']) ? trim($_GET['search'])    : '';

        // Đảm bảo page không âm
        if ($page < 1) {$page = 1;}

        $offset = ($page - 1) * $limit;

        $sinhvienModel = $this->model('sinhvienModel');
        $data          = $sinhvienModel->paging($limit, $offset, $search);

        $this->view("layout/masterlayout", [
            'viewname'    => 'sinhvien/index',
            'sinhviens'   => $data['sinhviens'],
            'totalPage'   => $data['totalpage'],
            'currentPage' => $page,
            'search'      => $search,
            'limit'       => $limit
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
