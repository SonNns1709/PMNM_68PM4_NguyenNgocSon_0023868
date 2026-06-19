<?php
require_once '../app/core/Controller.php';

class Sinhvien extends Controller
{
    // Định nghĩa các hằng số để tránh trùng lặp chuỗi (Fix SonarQube S1192)
    private const MASTER_LAYOUT = "layout/masterlayout";
    private const REDIRECT_PATH = '/sinhvien/index';
    private const HEADER_LOCATION = 'Location: ';

    public function index()
    {
        Middleware::protect();

        $limit   = 5;
        $page    = isset($_GET['page'])    ? (int)  $_GET['page']    : 1;
        $search  = isset($_GET['search'])  ? trim(  $_GET['search']) : '';
        $xepLoai = isset($_GET['xepLoai']) ? trim(  $_GET['xepLoai']) : '';
        $nganh   = isset($_GET['nganh'])   ? trim(  $_GET['nganh'])   : '';
        $lop     = isset($_GET['lop'])     ? trim(  $_GET['lop']     ) : '';
        $sortBy  = isset($_GET['sortBy'])  ? trim(  $_GET['sortBy']) : 'id';
        $sortDir = isset($_GET['sortDir']) ? trim(  $_GET['sortDir']) : 'ASC';

        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        $sinhvienModel = $this->model('sinhvienModel');
        $data = $sinhvienModel->paging($limit, $offset, $search, $xepLoai, $nganh, $lop, $sortBy, $sortDir);

        $this->view(self::MASTER_LAYOUT, [
            'viewname'      => 'sinhvien/index',
            'sinhviens'     => $data['sinhviens'],
            'totalPage'     => $data['totalpage'],
            'currentPage'   => $page,
            'search'        => $search,
            'xepLoai'       => $xepLoai,
            'nganh'         => $nganh,
            'lop'           => $lop,
            'sortBy'        => $sortBy,
            'sortDir'       => $sortDir,
            'danhSachNganh' => $sinhvienModel->getAllNganh(),
            'danhSachLop'   => $sinhvienModel->getAllLop(),
            'limit'         => $limit
        ]);
    }

    public function create()
    {
        Middleware::protect();

        $model       = $this->model('sinhvienModel');
        $monhocs     = $model->getAllMonhocs();
        $danhSachLop = $model->getAllLop();
        $error       = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten    = trim($_POST['hoten']    ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv     = trim($_POST['mssv']     ?? '');
            $nganh    = trim($_POST['nganh']    ?? '');
            
            // XỬ LÝ TRIỆT ĐỂ: Kiểm tra tồn tại, loại bỏ mảng và ép về số nguyên hoặc null
            $lopPost  = $_POST['lop_id'] ?? null;
            $lopId    = (!empty($lopPost) && !is_array($lopPost)) ? (int)$lopPost : null;
            
            $ghiChu   = trim($_POST['ghi_chu']  ?? '');
            $diems    = $_POST['diems']         ?? [];

            if (empty($hoten) || empty($gioitinh) || empty($mssv) || empty($nganh)) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
            } else {
                $result = $model->create($hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $diems);

                if ($result['success']) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Thêm sinh viên thành công!'];
                    header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
                    exit();
                } else {
                    $error = $result['error']; // Lỗi trùng MSSV lưu vào đây
                }
            }
        }

        $this->view(self::MASTER_LAYOUT, [
            'viewname'    => 'sinhvien/themsv',
            'monhocs'     => $monhocs,
            'danhSachLop' => $danhSachLop,
            'error'       => $error
        ]);
    }

    public function edit(int $id)
    {
        Middleware::protect();

        $model       = $this->model('sinhvienModel');
        $sinhvien    = $model->getById($id);
        $monhocs     = $model->getAllMonhocs();
        $danhSachLop = $model->getAllLop();

        if (!$sinhvien) {
            header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
            exit();
        }

        $bangdiem = $model->getBangDiemBySinhvienId($id);
        $diemMap  = [];
        foreach ($bangdiem as $d) {
            $diemMap[$d['monhoc_id']] = $d;
        }
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten    = trim($_POST['hoten']    ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv     = trim($_POST['mssv']     ?? '');
            $nganh    = trim($_POST['nganh']    ?? '');
            
            // XỬ LÝ TRIỆT ĐỂ cho trang sửa sinh viên
            $lopPost  = $_POST['lop_id'] ?? null;
            $lopId    = (!empty($lopPost) && !is_array($lopPost)) ? (int)$lopPost : null;
            
            $ghiChu   = trim($_POST['ghi_chu']  ?? '');
            $diems    = $_POST['diems']         ?? [];

            if (empty($hoten) || empty($gioitinh) || empty($mssv) || empty($nganh)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
                $sinhvien = array_merge($sinhvien, [
                    'hoten' => $hoten, 'gioitinh' => $gioitinh, 'mssv' => $mssv,
                    'nganh' => $nganh, 'lop_id' => $lopId, 'ghi_chu' => $ghiChu
                ]);
            } else {
                $result = $model->update($id, $hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $diems);

                if ($result['success']) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => '💾 Cập nhật thành công!'];
                    header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
                    exit();
                } else {
                    $error = $result['error']; // Lỗi trùng khi cập nhật lưu vào đây
                }
            }
        }

        $this->view(self::MASTER_LAYOUT, [
            'viewname'    => 'sinhvien/edit',
            'sinhvien'    => $sinhvien,
            'monhocs'     => $monhocs,
            'diemMap'     => $diemMap,
            'danhSachLop' => $danhSachLop,
            'error'       => $error
        ]);
    }

    public function delete(int $id): void
    {
        Middleware::protect();

        $model = $this->model('sinhvienModel');
        $model->delete($id);

        header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
        exit();
    }

    public function diem(int $id): void
    {
        Middleware::protect();

        $model    = $this->model('sinhvienModel');
        $sinhvien = $model->getById($id);

        if (!$sinhvien) {
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => '🗑️ Đã xóa sinh viên khỏi hệ thống.'];
            header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
            exit();
        }

        $bangdiem = $model->getBangDiemBySinhvienId($id);

        $tongTC = 0;
        $tongDiem = 0;
        foreach ($bangdiem as $mon) {
            $tongTC   += $mon['so_tin_chi'];
            $tongDiem += $mon['diem_tong_ket'] * $mon['so_tin_chi'];
        }
        $gpa = $tongTC > 0 ? round($tongDiem / $tongTC, 2) : null;

        $this->view(self::MASTER_LAYOUT, [
            'viewname'  => 'sinhvien/diem',
            'sinhvien'  => $sinhvien,
            'bangdiem'  => $bangdiem,
            'gpa'       => $gpa
        ]);
    }
}
