<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $sinhviens
 * @var string $search
 * @var int $currentPage
 * @var int $limit
 * @var int $totalPage
 * @var string $xepLoai
 * @var string $nganh
 * @var array $danhSachNganh
 */
?>

<?php
// FIX SONARQUBE: Cập nhật hệ thống màu nền tương thích chuẩn Subtle Badge số 5
function getXepLoai(?float $gpa): array {
    if ($gpa === null) {
        return ['text' => 'Chưa có điểm', 'class' => 'secondary-subtle text-secondary', 'icon' => 'bi-dash-circle'];
    }
    
    $thresholds = [
        ['min' => 8.5, 'text' => 'Xuất Sắc',  'class' => 'success-subtle text-success', 'icon' => 'bi-star-fill'],
        ['min' => 7.0, 'text' => 'Giỏi',      'class' => 'primary-subtle text-primary', 'icon' => 'bi-trophy-fill'],
        ['min' => 5.5, 'text' => 'Khá',       'class' => 'info-subtle text-info',       'icon' => 'bi-hand-thumbs-up-fill'],
        ['min' => 4.0, 'text' => 'Trung Bình', 'class' => 'warning-subtle text-warning', 'icon' => 'bi-bar-chart-fill']
    ];

    foreach ($thresholds as $tier) {
        if ($gpa >= $tier['min']) {
            return $tier;
        }
    }

    return ['text' => 'Yếu', 'class' => 'danger-subtle text-danger', 'icon' => 'bi-exclamation-triangle-fill'];
}
?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-person-lines-fill text-primary"></i> Danh sách Sinh Viên
    </h4>
    <a href="<?= BASE_URL ?>/sinhvien/create" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 fw-medium shadow-sm" style="background:#3f51b5; border-color:#3f51b5;">
        <i class="bi bi-person-plus-fill"></i> Thêm sinh viên mới
    </a>
</div>

<form method="GET" action="<?= BASE_URL ?>/sinhvien/index" class="mb-4">
    <input type="hidden" name="url" value="sinhvien/index">
    <div class="row g-2 align-items-center">
        
        <div class="col-12 col-md-4">
            <div class="input-group border rounded-3 overflow-hidden bg-white">
                <span class="input-group-text bg-transparent border-0 text-secondary ps-3"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-transparent ps-2"
                       placeholder="Tìm theo tên hoặc MSSV..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>

        <div class="col-6 col-md-2">
            <select name="xepLoai" class="form-select rounded-3 text-secondary" aria-label="Lọc theo xếp loại">
                <option value="" <?= $xepLoai === '' ? 'selected' : '' ?>>Học lực: Tất cả</option>
                <option value="xuat_sac" <?= $xepLoai === 'xuat_sac' ? 'selected' : '' ?>>⭐ Xuất Sắc</option>
                <option value="gioi" <?= $xepLoai === 'gioi' ? 'selected' : '' ?>>🥇 Giỏi</option>
                <option value="kha" <?= $xepLoai === 'kha' ? 'selected' : '' ?>>👍 Khá</option>
                <option value="trung_binh" <?= $xepLoai === 'trung_binh' ? 'selected' : '' ?>>📊 Trung Bình</option>
                <option value="yeu" <?= $xepLoai === 'yeu' ? 'selected' : '' ?>>⚠️ Yếu</option>
            </select>
        </div>

        <div class="col-6 col-md-3">
            <select name="nganh" class="form-select rounded-3 text-secondary">
                <option value="">Chuyên ngành: Tất cả</option>
                <?php foreach($danhSachNganh as $ng): ?>
                <option value="<?= htmlspecialchars($ng) ?>" <?= $nganh === $ng ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ng) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary rounded-3 px-3 flex-grow-1 fw-medium">Lọc dữ liệu</button>
            <?php if (!empty($search) || !empty($xepLoai) || !empty($nganh)): ?>
                <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-outline-secondary rounded-3 d-inline-flex align-items-center justify-content-center" title="Xoá tất cả bộ lọc"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </div>

    </div>
</form>

<div class="table-responsive rounded-4 border bg-white shadow-sm mb-4">
  <table class="table table-hover align-middle mb-0">
    <thead>
      <tr class="table-active">
        <th class="fw-semibold py-3 ps-4" style="width: 70px">STT</th>
        <th class="fw-semibold py-3">Họ Tên</th>
        <th class="fw-semibold py-3" style="width: 120px">Giới Tính</th>
        <th class="fw-semibold py-3" style="width: 140px">MSSV</th>
        <th class="fw-semibold py-3">Chuyên Ngành</th>
        <th class="fw-semibold py-3 text-center" style="width: 90px">GPA</th>
        <th class="fw-semibold py-3" style="width: 150px">Xếp Loại</th>
        <th class="fw-semibold py-3 text-center pe-4" style="width: 150px">Hành Động</th>
      </tr>
    </thead>
    <tbody class="border-top-0">
      <?php if (empty($sinhviens)): ?>
      <tr>
        <td colspan="8" class="text-center text-muted py-5">
          <i class="bi bi-person-fill-x fs-1 d-block mb-2 text-black-50"></i>
          <?= !empty($search)
              ? "Không tìm thấy sinh viên phù hợp với từ khóa \"" . htmlspecialchars($search) . "\""
              : "Hệ thống chưa ghi nhận dữ liệu sinh viên nào." ?>
        </td>
      </tr>
      <?php else: ?>
      <?php foreach ($sinhviens as $i => $sv): ?>
      <?php $stt = ($currentPage - 1) * $limit + $i + 1; ?>
      <tr>
        <td class="ps-4 py-3 text-secondary"><?= $stt ?></td>
        <td class="fw-medium text-dark"><?= htmlspecialchars($sv['hoten']) ?></td>
        <td>
          <span class="badge rounded-pill px-25 py-1 fw-medium <?= $sv['gioitinh'] === 'Nam' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' ?>">
             <i class="bi <?= $sv['gioitinh'] === 'Nam' ? 'bi-gender-male' : 'bi-gender-female' ?>"></i> <?= htmlspecialchars($sv['gioitinh']) ?>
          </span>
        </td>
        <td><code class="text-dark fw-medium"><?= htmlspecialchars($sv['mssv']) ?></code></td>
        <td>
          <span class="badge rounded-pill bg-secondary-subtle text-secondary px-25 py-1 fw-medium">
            <?= htmlspecialchars($sv['nganh'] ?? 'Chưa xác định') ?>
          </span>
        </td>
        
        <?php
            $gpa    = $sv['gpa'] !== null ? (float)$sv['gpa'] : null;
            $xl     = getXepLoai($gpa);
            $color  = ($gpa !== null && $gpa >= 5.0) ? '#1d9e75' : '#dc3545';
        ?>
        <td class="text-center">
            <?php if ($gpa !== null): ?>
            <strong style="color:<?= $color ?>; font-size: 15px;"><?= number_format($gpa, 2) ?></strong>
            <?php else: ?>
            <span class="text-muted small">—</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="badge rounded-pill bg-<?= $xl['class'] ?> d-inline-flex align-items-center gap-1 px-25 py-1 fw-medium">
               <i class="<?= $xl['icon'] ?>" style="font-size: 11px;"></i> <?= $xl['text'] ?>
            </span>
        </td>
        
        <td class="text-center pe-4">
            <div class="d-inline-flex gap-1">
                <a href="<?= BASE_URL ?>/sinhvien/diem/<?= $sv['id'] ?>"
                   class="btn btn-sm btn-outline-primary rounded-circle custom-action-btn" title="Xem bảng điểm">
                   <i class="bi bi-card-checklist"></i>
                </a>
                  
                <a href="<?= BASE_URL ?>/sinhvien/edit/<?= $sv['id'] ?>"
                   class="btn btn-sm btn-outline-warning rounded-circle custom-action-btn" title="Sửa thông tin">
                   <i class="bi bi-pencil"></i>
                </a>
                  
                <a href="<?= BASE_URL ?>/sinhvien/delete/<?= $sv['id'] ?>"
                   class="btn btn-sm btn-outline-danger rounded-circle custom-action-btn" title="Xóa sinh viên"
                   onclick="return confirm('Hệ thống sẽ xóa toàn bộ điểm số của sinh viên này. Bạn chắc chắn muốn xóa <?= htmlspecialchars($sv['hoten']) ?>?')">
                   <i class="bi bi-trash3"></i>
                </a>
            </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($totalPage > 1): ?>
<nav aria-label="Điều hướng phân trang" class="mt-4">
  <ul class="pagination justify-content-center flex-wrap gap-1 border-0">

    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
      <a class="page-link rounded-3 border-0 bg-light text-secondary px-3"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>">
         <i class="bi bi-chevron-left small"></i> Trước
      </a>
    </li>

    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
      <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
        <a class="page-link rounded-3 border-0 px-3 fw-medium mx-05 <?= $i === $currentPage ? 'shadow-sm' : 'bg-light text-dark' ?>"
          style="<?= $i === $currentPage ? 'background:#3f51b5 !important; color:#ffffff !important;' : '' ?>"
          href="<?= BASE_URL ?>/sinhvien/index?page=<?= $i ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>"
          aria-label="Trang <?= $i ?>" <?= $i === $currentPage ? 'aria-current="page"' : '' ?>>
          <?= $i ?>
        </a>
      </li>
    <?php endfor; ?>

    <li class="page-item <?= $currentPage >= $totalPage ? 'disabled' : '' ?>">
      <a class="page-link rounded-3 border-0 bg-light text-secondary px-3"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>">
         Tiếp <i class="bi bi-chevron-right small"></i>
      </a>
    </li>

  </ul>
</nav>

<div class="text-center text-secondary mt-2" style="font-size: 13px;">
    Hiển thị trang <span class="fw-semibold text-dark"><?= $currentPage ?></span> trên tổng số <span class="fw-semibold text-dark"><?= $totalPage ?></span> trang
</div>
<?php endif; ?>

<style>
.py-25 {
    padding-top: 0.38rem !important;
    padding-bottom: 0.38rem !important;
}
.px-25 {
    padding-left: 0.65rem !important;
    padding-right: 0.65rem !important;
}
.mx-05 {
    margin-left: 0.15rem !important;
    margin-right: 0.15rem !important;
}
/* Hiệu ứng tương tác mượt mà cho các nút hành động dạng vòng tròn nhỏ */
.custom-action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 13px;
    transition: all 0.2s ease-in-out;
}
.custom-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
/* Focus hiệu ứng viền hộp lọc dữ liệu */
.input-group:focus-within {
    border-color: #3f51b5 !important;
    box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.12);
}
.form-select:focus {
    border-color: var(--bs-border-color);
    box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.12) !important;
}

.form-select {
      /* Ép buộc hiển thị icon dropdown cho các ô select trong thanh tìm kiếm */
    appearance: none !important; /* Xoá bỏ định dạng mặc định lỗi thời của trình duyệt */
    -webkit-appearance: none !important;
    -moz-appearance: none !important;

    /* Nhúng trực tiếp SVG hình mũi tên Dropdown chuẩn của Bootstrap 5 */
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 12px center !important; /* Đặt vị trí icon cách lề phải 12px */
    background-size: 16px 12px !important; /* Định kích cỡ icon vừa vặn */
    padding-right: 36px !important; /* Đẩy chữ qua trái để không đè lên mũi tên */

    /* Đảm bảo khi ghép các ô lại với nhau, đường viền phân tách sắc nét */
    border-right: 1px solid #dee2e6 !important;
}
</style>
