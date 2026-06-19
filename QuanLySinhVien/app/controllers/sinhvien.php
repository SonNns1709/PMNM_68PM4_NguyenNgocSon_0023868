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
        // 1. Nhấc biến lấy dữ liệu ngành lên trên đầu để có giá trị sử dụng
        $nganh   = isset($_GET['nganh'])   ? trim(  $_GET['nganh'])   : '';

        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        $sinhvienModel = $this->model('sinhvienModel');
        
        // 2. Lấy danh sách toàn bộ ngành để hiển thị lên bộ lọc (Filter) ở giao diện
        $danhSachNganh = $sinhvienModel->getAllNganh();

        // 3. Chạy hàm phân trang có chứa tham số $nganh để lọc chính xác dữ liệu
        $data          = $sinhvienModel->paging($limit, $offset, $search, $xepLoai, $nganh);

        // 4. Truyền dữ liệu sang View (Lúc này các biến đều đã hợp lệ và có dữ liệu)
        $this->view(self::MASTER_LAYOUT, [
            'viewname'    => 'sinhvien/index',
            'sinhviens'   => $data['sinhviens'],
            'totalPage'   => $data['totalpage'],
            'currentPage' => $page,
            'search'      => $search,
            'xepLoai'     => $xepLoai,
            'limit'       => $limit,
            'nganh'       => $nganh,          // Không còn bị lỗi Unexpected '=>' nữa
            'danhSachNganh' => $danhSachNganh,
        ]);
    }

   public function create()
    {
        Middleware::protect();

        $model   = $this->model('sinhvienModel');
        $monhocs = $model->getAllMonhocs();
        $error   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten    = trim($_POST['hoten']    ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv     = trim($_POST['mssv']     ?? '');
            $nganh    = trim($_POST['nganh']    ?? '');
            $lopId    = $_POST['lop_id']        ?? null; 
            $ghiChu   = $_POST['ghi_chu']       ?? '';
            $diems    = $_POST['diems']         ?? [];

            if (empty($hoten) || empty($gioitinh) || empty($mssv) || empty($nganh)) {
                $error = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
            } else {
                // Hứng mảng trả về từ Model
                $result = $model->create($hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $diems);
                
                if ($result['success'] === true) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Thêm sinh viên thành công!'];
                    header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
                    exit();
                } else {
                    // Lấy chính xác câu thông báo lỗi trùng dịch từ Model ra
                    $error = $result['error'];
                }
            }
        }

        $this->view(self::MASTER_LAYOUT, [
            'viewname' => 'sinhvien/themsv',
            'monhocs'  => $monhocs,
            'error'    => $error
        ]);
    }

    public function edit(int $id)
    {
        Middleware::protect();

        $model    = $this->model('sinhvienModel');
        $sinhvien = $model->getById($id);
        $monhocs  = $model->getAllMonhocs();

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
            $lopId    = $_POST['lop_id']        ?? null;
            $ghiChu   = $_POST['ghi_chu']       ?? '';
            $diems    = $_POST['diems']         ?? [];

            if (empty($hoten) || empty($gioitinh) || empty($mssv) || empty($nganh)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
                $sinhvien = array_merge($sinhvien, compact('hoten', 'gioitinh', 'mssv', 'nganh', 'lopId', 'ghiChu'));
            } else {
                // Hứng mảng trả về từ Model để kiểm tra trùng cho hàm update
                $result = $model->update($id, $hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $diems);
                
                if ($result['success'] === true) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => '💾 Cập nhật thông tin thành công!'];
                    header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
                    exit();
                } else {
                    $error = $result['error'];
                    $sinhvien = array_merge($sinhvien, compact('hoten', 'gioitinh', 'mssv', 'nganh', 'lopId', 'ghiChu'));
                }
            }
        }

        $this->view(self::MASTER_LAYOUT, [
            'viewname' => 'sinhvien/edit',
            'sinhvien' => $sinhvien,
            'monhocs'  => $monhocs,
            'diemMap'  => $diemMap,
            'error'    => $error
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
            $_SESSION['flash'] = ['type'=>'warning','msg'=>'🗑️ Đã xóa sinh viên khỏi hệ thống.'];
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
