<?php
require_once '../app/core/Controller.php';

class Sinhvien extends Controller
{
    // Định nghĩa các hằng số để tránh trùng lặp chuỗi (Fix SonarQube S1192)
    private const MASTER_LAYOUT = "layout/masterlayout";
    private const REDIRECT_PATH = '/sinhvien/index';
    private const HEADER_LOCATION = 'Location: ';
    private const UPLOAD_DIR = 'public/uploads/sinhviens/';

    public function index()
    {
        Middleware::protect();

        $allowedLimits = [5, 10, 20, 50];
        $limit = isset($_GET['limit']) && in_array((int)$_GET['limit'], $allowedLimits)
                 ? (int)$_GET['limit'] : 5;

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

        $totalRecord = $data['totalrecord'];
        $recordStart = $totalRecord > 0 ? $offset + 1 : 0;
        $recordEnd   = min($offset + $limit, $totalRecord);

        $this->view(self::MASTER_LAYOUT, [
            'viewname'      => 'sinhvien/index',
            'sinhviens'     => $data['sinhviens'],
            'totalPage'     => $data['totalpage'],
            'totalRecord'   => $totalRecord,
            'recordStart'   => $recordStart,
            'recordEnd'     => $recordEnd,
            'currentPage'   => $page,
            'search'        => $search,
            'xepLoai'       => $xepLoai,
            'nganh'         => $nganh,
            'lop'           => $lop,
            'sortBy'        => $sortBy,
            'sortDir'       => $sortDir,
            'danhSachNganh' => $sinhvienModel->getAllNganh(),
            'danhSachLop'   => $sinhvienModel->getAllLop(),
            'limit'         => $limit,
            'allowedLimits' => $allowedLimits
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
            $error = $this->processCreateSubmit($model);
        }

        $this->view(self::MASTER_LAYOUT, [
            'viewname'    => 'sinhvien/themsv',
            'monhocs'     => $monhocs,
            'danhSachLop' => $danhSachLop,
            'error'       => $error
        ]);
    }

    private function processCreateSubmit(object $model): string
    {
        $hoten    = trim($_POST['hoten']    ?? '');
        $gioitinh = trim($_POST['gioitinh'] ?? '');
        $mssv     = trim($_POST['mssv']     ?? '');
        $nganh    = trim($_POST['nganh']    ?? '');
        
        $lopPost  = $_POST['lop_id'] ?? null;
        $lopId    = (!empty($lopPost) && !is_array($lopPost)) ? (int)$lopPost : null;
        
        $ghiChu   = trim($_POST['ghi_chu']  ?? '');
        $diems    = $_POST['diems']         ?? [];

        if (empty($hoten) || empty($gioitinh) || empty($mssv) || empty($nganh)) {
            return 'Vui lòng điền đầy đủ thông tin bắt buộc!';
        }

        // SỬA TẠI ĐÂY: Mặc định ban đầu để trống
        $anhDaiDien = '';

        // Kiểm tra chắc chắn người dùng CÓ chọn file và file KHÔNG bị lỗi hệ thống
        if (!empty($_FILES['anh_dai_dien']['name']) && $_FILES['anh_dai_dien']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadAnh($_FILES['anh_dai_dien']);
            if ($uploadResult['success']) {
                $anhDaiDien = $uploadResult['filename']; // Lưu tên file mới sinh ra
            } else {
                return $uploadResult['error']; // Trả về thông báo nếu ảnh lỗi (quá dung lượng, sai đuôi...)
            }
        }

        // Truyền $anhDaiDien vào Model (Chắc chắn lúc này là string: tên_file.png hoặc '')
        $result = $model->create($hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $anhDaiDien, $diems);
        if ($result['success']) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Thêm sinh viên thành công!'];
            header(self::HEADER_LOCATION . BASE_URL . self::REDIRECT_PATH);
            exit();
        }

        return $result['error'];
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

        // Khi người dùng bấm nút Submit nút lưu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Gọi hàm xử lý mà chúng ta đã sửa (Lưu ý truyền tham chiếu &$sinhvien)
            $error = $this->processEditSubmit($model, $id, $sinhvien);

            // NẾU KHÔNG CÓ LỖI ($errors === null) -> CHUYỂN TRANG NGAY
            if ($error === null) {
                header('Location: ' . BASE_URL . '/sinhvien/index');
                exit();
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

    private function processEditSubmit(object $model, int $id, array &$sinhvien): ?string
    {
        $hoten    = trim($_POST['hoten'] ?? '');
        $gioitinh = trim($_POST['gioitinh'] ?? '');
        $mssv     = trim($_POST['mssv'] ?? '');
        $nganh    = trim($_POST['nganh'] ?? '');
        $lopId    = !empty($_POST['lop_id']) ? (int)$_POST['lop_id'] : null;
        $ghiChu   = trim($_POST['ghi_chu'] ?? '');
        $diems    = $_POST['diem'] ?? [];

        // Giữ lại tên ảnh cũ làm mặc định
        $anhDaiDien = $sinhvien['anh_dai_dien'] ?? null;

        // Xử lý nếu người dùng có chọn file ảnh mới để upload
        if (!empty($_FILES['anh_dai_dien']['name'])) {
            $uploadResult = $this->uploadAnh($_FILES['anh_dai_dien']);
            if (!$uploadResult['success']) {
                return $uploadResult['error'];
            }
            
            // Xoá file ảnh cũ trong thư mục nếu có
            if (!empty($sinhvien['anh_dai_dien']) && file_exists(self::UPLOAD_DIR . $sinhvien['anh_dai_dien'])) {
                @unlink(self::UPLOAD_DIR . $sinhvien['anh_dai_dien']);
            }
            
            // Gán tên file mới sinh ra
            $anhDaiDien = $uploadResult['filename'];
            $sinhvien['anh_dai_dien'] = $uploadResult['filename'];
        }

        // Gọi Model thực hiện cập nhật vào Database
        // ĐẢM BẢO biến $anhDaiDien được truyền đúng vị trí tham số thứ 8
        $result = $model->update($id, $hoten, $gioitinh, $mssv, $nganh, $lopId, $ghiChu, $anhDaiDien, $diems);

        if (!$result['success']) {
            return $result['error'];
        }

        return null; // Trả về null tức là không có lỗi xảy ra
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

    private function uploadAnh(array $file): array
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize    = 2 * 1024 * 1024; // 2MB
        $ext        = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));

        $errorMsg = null;
        $filename = '';

        // 1. Kiểm tra các điều kiện lỗi đầu vào
        if (!in_array($ext, $allowedExt)) {
            $errorMsg = 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP!';
        } elseif (($file['size'] ?? 0) > $maxSize) {
            $errorMsg = 'Ảnh không được vượt quá 2MB!';
        } elseif (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $errorMsg = 'Lỗi khi tải ảnh lên, vui lòng thử lại!';
        } else {
            // FIX ĐƯỜNG DẪN: Buộc phải lưu vào thư mục public để trình duyệt đọc được công khai
            $uploadDir = self::UPLOAD_DIR;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = uniqid('sv_', true) . '.' . $ext;
            
            // Thực hiện di chuyển file ảnh vào thư mục đích
            if (!move_uploaded_file($file['tmp_name'] ?? '', $uploadDir . $filename)) {
                $errorMsg = 'Không thể lưu ảnh vào server!';
            }
        }

        // 2. Chỉ sử dụng đúng 2 lệnh return duy nhất để thỏa mãn SonarQube S1142
        if ($errorMsg !== null) {
            return ['success' => false, 'error' => $errorMsg];
        }

        return ['success' => true, 'filename' => $filename];
    }
}
