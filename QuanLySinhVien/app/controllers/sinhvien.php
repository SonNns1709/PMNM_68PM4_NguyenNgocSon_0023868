<?php
require_once '../app/core/Controller.php';

class Sinhvien extends Controller
{
    // FIX SONARQUBE (php:S1192): Định nghĩa các hằng số để tránh lặp lại chuỗi ký tự
    private const MASTER_LAYOUT = "layout/masterlayout";
    private const REDIRECT_INDEX = 'Location: ' . BASE_URL . '/sinhvien/index';

    public function index(): void
    {
        Middleware::protect();

        $limit  = 5;
        $page   = isset($_GET['page'])   ? (int)   $_GET['page']   : 1;
        $search = isset($_GET['search']) ? trim($_GET['search'])    : '';

        // FIX SONARQUBE (php:S121): Viết lại cấu trúc block if chuẩn chỉnh đầy đủ xuống dòng
        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $limit;

        $sinhvienModel = $this->model('sinhvienModel');
        $data          = $sinhvienModel->paging($limit, $offset, $search);

        // FIX SONARQUBE: Thay chuỗi "layout/masterlayout" bằng hằng số self::MASTER_LAYOUT
        $this->view(self::MASTER_LAYOUT, [
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
                    // FIX SONARQUBE: Thay chuỗi redirect bằng hằng số self::REDIRECT_INDEX
                    header(self::REDIRECT_INDEX);
                    exit();
                } else {
                    $error = 'Thêm thất bại, MSSV có thể đã tồn tại!';
                }
            }
        }

        // FIX SONARQUBE: Thay chuỗi "layout/masterlayout" bằng hằng số self::MASTER_LAYOUT
        $this->view(self::MASTER_LAYOUT, [
            'viewname' => 'sinhvien/themsv',
            'error'    => $error
        ]);
    }

    /**
     * Cập nhật sinh viên
     * FIX INTELEPHENSE (P1132): Thêm type hint `int` cho biến $id
     */
    public function edit(int $id): void
    {
        Middleware::protect();

        $model   = $this->model('sinhvienModel');
        $error   = '';
        $sinhvien = $model->getById($id);

        // Nếu ID không tồn tại → về danh sách
        if (!$sinhvien) {
            // FIX SONARQUBE: Thay chuỗi redirect bằng hằng số self::REDIRECT_INDEX
            header(self::REDIRECT_INDEX);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten    = trim($_POST['hoten']    ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv     = trim($_POST['mssv']     ?? '');

            if (empty($hoten) || empty($gioitinh) || empty($mssv)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
                // Giữ lại dữ liệu người dùng vừa nhập
                $sinhvien['hoten']    = $hoten;
                $sinhvien['gioitinh'] = $gioitinh;
                $sinhvien['mssv']     = $mssv;
            } else {
                if ($model->update($id, $hoten, $gioitinh, $mssv)) {
                    // FIX SONARQUBE: Thay chuỗi redirect bằng hằng số self::REDIRECT_INDEX
                    header(self::REDIRECT_INDEX);
                    exit();
                } else {
                    $error = 'Cập nhật thất bại, MSSV có thể đã tồn tại!';
                }
            }
        }

        // FIX SONARQUBE: Thay chuỗi "layout/masterlayout" bằng hằng số self::MASTER_LAYOUT
        $this->view(self::MASTER_LAYOUT, [
            'viewname' => 'sinhvien/edit',
            'sinhvien' => $sinhvien,
            'error'    => $error
        ]);
    }
}
