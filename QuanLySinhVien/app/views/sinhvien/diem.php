<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $sinhvien
 * @var array $bangdiem
 * @var string $search
 * @var int $currentPage
 * @var int $limit
 * @var int $totalPage
 * @var string $xepLoai
 * @var mixed $gpa
 */
?>

<?php
// Hệ thống Helper xếp loại cập nhật class tương thích chuẩn Subtle Badge số 5
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
$xl = getXepLoai($gpa);
?>

<div class="card border-0 shadow-sm mb-4 rounded-4 custom-info-card"
     style="background: #ffffff; transition: transform .2s, box-shadow .2s;">
  <div class="card-body p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-md-items-center gap-3">
      <div>
        <h4 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: #2c3e50;">
          <i class="bi bi-card-checklist text-primary"></i> Bảng Điểm — <?= htmlspecialchars($sinhvien['hoten']) ?>
        </h4>
        <div class="d-flex flex-wrap gap-2 align-items-center" style="font-size: 14px;">
          <span class="text-secondary"><span class="fw-semibold">MSSV:</span> <?= htmlspecialchars($sinhvien['mssv']) ?></span>
          <span class="text-black-50">|</span>
          <span class="text-secondary"><span class="fw-semibold">Giới tính:</span> <?= htmlspecialchars($sinhvien['gioitinh']) ?></span>
        </div>
      </div>
      
      <div class="text-md-end d-flex flex-row flex-md-column justify-content-between align-items-center gap-2 bg-light p-3 rounded-4 px-4">
        <div>
          <div class="small text-secondary fw-medium" style="font-size:12px;">GPA Tích Lũy</div>
          <div class="lh-1 my-1" style="font-size:32px; font-weight:800; color: <?= ($gpa !== null && $gpa >= 5) ? '#1d9e75' : '#dc3545' ?>;">
            <?= $gpa !== null ? number_format((float)$gpa, 2) : '—' ?>
          </div>
        </div>
        <span class="badge rounded-pill bg-<?= $xl['class'] ?> d-inline-flex align-items-center gap-1 px-3 py-2 fw-semibold">
           <i class="<?= $xl['icon'] ?>"></i> <?= $xl['text'] ?>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="table-responsive rounded-4 border bg-white shadow-sm mb-4">
  <table class="table table-hover align-middle mb-0">
    <thead>
      <tr class="table-active">
        <th class="fw-semibold py-3 ps-4" style="width: 70px;">STT</th>
        <th class="fw-semibold py-3">Mã Môn</th>
        <th class="fw-semibold py-3">Tên Môn Học</th>
        <th class="fw-semibold py-3 text-center">Tín Chỉ</th>
        <th class="fw-semibold py-3 text-center">Điểm CC</th>
        <th class="fw-semibold py-3 text-center">Điểm GK</th>
        <th class="fw-semibold py-3 text-center">Điểm CK</th>
        <th class="fw-semibold py-3 text-center">Tổng Kết</th>
        <th class="fw-semibold py-3 text-center pe-4" style="width: 130px;">Trạng Thái</th>
      </tr>
    </thead>
    <tbody class="border-top-0">
      <?php if (empty($bangdiem)): ?>
      <tr>
        <td colspan="9" class="text-center text-muted py-5">
          <i class="bi bi-inbox fs-2 d-block mb-2 text-black-50"></i>
          Sinh viên này chưa có điểm môn học nào trong hệ thống.
        </td>
      </tr>
      <?php else: ?>
      <?php foreach ($bangdiem as $i => $mon): ?>
      <?php
          $tk       = (float) $mon['diem_tong_ket'];
          $datMon   = $tk >= 4.0;
          $tkColor  = $tk >= 5.0 ? '#1d9e75' : '#dc3545';
      ?>
      <tr>
        <td class="ps-4 py-3 text-secondary"><?= $i+1 ?></td>
        <td><code><?= htmlspecialchars($mon['ma_mon']) ?></code></td>
        <td class="fw-medium text-dark"><?= htmlspecialchars($mon['ten_mon']) ?></td>
        <td class="text-center text-secondary"><?= $mon['so_tin_chi'] ?></td>
        <td class="text-center text-secondary"><?= $mon['diem_chuyen_can'] ?></td>
        <td class="text-center text-secondary"><?= $mon['diem_giua_ky'] ?></td>
        <td class="text-center text-secondary"><?= $mon['diem_cuoi_ky'] ?></td>
        <td class="text-center fw-bold fs-6" style="color: <?= $tkColor ?>;">
          <?= number_format($tk, 1) ?>
        </td>
        <td class="text-center pe-4">
          <?php if ($datMon): ?>
          <span class="badge rounded-pill bg-success-subtle text-success px-3 py-15 fw-medium d-inline-flex align-items-center gap-1">
             <i class="bi bi-check-circle"></i> Đạt
          </span>
          <?php else: ?>
          <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-15 fw-medium d-inline-flex align-items-center gap-1">
             <i class="bi bi-x-circle"></i> Học lại
          </span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="mt-3">
  <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-outline-secondary rounded-pill px-4 custom-back-btn">
     <i class="bi bi-arrow-left"></i> Quay lại danh sách
  </a>
</div>

<style>
/* Hiệu ứng nổi nhẹ cho khối card thông tin */
.custom-info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .06) !important;
}

/* Định kích cỡ nhẹ cho padding badge trạng thái trong bảng */
.py-15 {
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
}

/* Hiệu ứng di chuyển mượt mà của nút Quay lại */
.custom-back-btn {
    transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
}
.custom-back-btn:hover {
    transform: translateX(-4px);
}
</style>
