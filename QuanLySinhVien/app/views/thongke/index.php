<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $tongQuan
 * @var array $theoNganh
 * @var array $xepLoai
 */
?>

<div class="d-flex align-items-center gap-2 mb-4">
    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-bar-chart-line-fill text-primary" style="color: #3f51b5 !important;"></i> Báo Cáo & Thống Kê Tổng Quan
    </h4>
</div>

<div class="row g-3 mb-4">
  <?php
  // Định nghĩa lại mảng dữ liệu thẻ với hệ thống Bootstrap Icons và Class CSS cao cấp
  $cards = [
    [
        'label' => 'Tổng Sinh Viên',
        'val'   => $tongQuan['total_sv'],
        'icon'  => 'bi-people-fill',
        'bg'    => 'bg-primary-subtle text-primary',
        'color' => '#3f51b5'
    ],
    [
        'label' => 'GPA Cao Nhất',
        'val'   => $tongQuan['max_gpa'] !== null ? number_format($tongQuan['max_gpa'], 2) : '0.00',
        'icon'  => 'bi-trophy-fill',
        'bg'    => 'bg-success-subtle text-success',
        'color' => '#1d9e75'
    ],
    [
        'label' => 'GPA Thấp Nhất',
        'val'   => $tongQuan['min_gpa'] !== null ? number_format($tongQuan['min_gpa'], 2) : '0.00',
        'icon'  => 'bi-graph-down-arrow',
        'bg'    => 'bg-danger-subtle text-danger',
        'color' => '#dc3545'
    ],
    [
        'label' => 'GPA Trung Bình',
        'val'   => $tongQuan['avg_gpa'] !== null ? number_format($tongQuan['avg_gpa'], 2) : '0.00',
        'icon'  => 'bi-calculator-fill',
        'bg'    => 'bg-info-subtle text-info',
        'color' => '#00b4d8'
    ],
  ];
  
  foreach($cards as $c): ?>
  <div class="col-6 col-md-3">
    <div class="card border rounded-4 shadow-sm h-100 bg-white p-3 custom-stat-card">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-medium text-uppercase tracking-wider"><?= $c['label'] ?></span>
        <div class="rounded-circle d-flex align-items-center justify-content-center custom-icon-box <?= $c['bg'] ?>">
          <i class="bi <?= $c['icon'] ?>"></i>
        </div>
      </div>
      <h3 class="fw-bold mb-0" style="color: <?= $c['color'] ?>; font-size: 1.65rem;"><?= $c['val'] ?></h3>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  
  <div class="col-md-7">
    <div class="card border rounded-4 shadow-sm bg-white overflow-hidden h-100">
      <div class="card-header border-0 bg-transparent pt-3 px-3 pb-2">
        <strong class="text-dark d-flex align-items-center gap-2">
          <i class="bi bi-journal-bookmark-fill text-secondary"></i> Phân Tích Theo Chuyên Ngành
        </strong>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="table-light text-secondary small">
                <th class="fw-semibold py-3 ps-3">Tên Chuyên Ngành</th>
                <th class="fw-semibold py-3 text-center" style="width: 110px;">Số Lượng SV</th>
                <th class="fw-semibold py-3 text-center pe-3" style="width: 120px;">GPA Trung Bình</th>
              </tr>
            </thead>
            <tbody class="border-top-0">
              <?php if (empty($theoNganh)): ?>
              <tr>
                <td colspan="3" class="text-center text-muted py-4 small">Chưa có dữ liệu phân tích ngành.</td>
              </tr>
              <?php else: ?>
              <?php foreach($theoNganh as $row):
                $gpaVal = $row['avg_gpa'] !== null ? (float)$row['avg_gpa'] : null;
                $gpaColor = ($gpaVal !== null && $gpaVal >= 5.0) ? '#1d9e75' : '#dc3545';
              ?>
              <tr>
                <td class="ps-3 fw-medium text-dark"><?= htmlspecialchars($row['nganh'] ?? 'Chưa xác định') ?></td>
                <td class="text-center">
                  <span class="badge rounded-pill bg-secondary-subtle text-secondary px-25 py-1 fw-medium">
                    <?= (int)$row['so_sv'] ?>
                  </span>
                </td>
                <td class="text-center pe-3 fw-bold" style="color: <?= $gpaColor ?>;">
                  <?= $gpaVal !== null ? number_format($gpaVal, 2) : '—' ?>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-5">
    <div class="card border rounded-4 shadow-sm bg-white overflow-hidden h-100">
      <div class="card-header border-0 bg-transparent pt-3 px-3 pb-2">
        <strong class="text-dark d-flex align-items-center gap-2">
          <i class="bi bi-pie-chart-fill text-secondary"></i> Cơ Cấu Phân Bổ Học Lực
        </strong>
      </div>
      <div class="card-body p-0">
        <?php
        // Áp dụng Subtle Badge đồng bộ trang Index tránh màu chói
        $xlColors = [
            'Xuất Sắc'  => 'success-subtle text-success',
            'Giỏi'      => 'primary-subtle text-primary',
            'Khá'       => 'info-subtle text-info',
            'Trung Bình'=> 'warning-subtle text-warning',
            'Yếu'       => 'danger-subtle text-danger'
        ];
        ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="table-light text-secondary small">
                <th class="fw-semibold py-3 ps-3">Tiêu Chí Xếp Loại</th>
                <th class="fw-semibold py-3 text-center pe-3" style="width: 140px;">Số Lượng Sinh Viên</th>
              </tr>
            </thead>
            <tbody class="border-top-0">
              <?php if (empty($xepLoai)): ?>
              <tr>
                <td colspan="2" class="text-center text-muted py-4 small">Chưa ghi nhận dữ liệu xếp loại.</td>
              </tr>
              <?php else: ?>
              <?php foreach($xepLoai as $xl):
                $badgeClass = $xlColors[$xl['xep_loai']] ?? 'secondary-subtle text-secondary';
              ?>
              <tr>
                <td class="ps-3">
                  <span class="badge rounded-pill px-25 py-1 fw-medium bg-<?= $badgeClass ?>">
                    <?= htmlspecialchars($xl['xep_loai']) ?>
                  </span>
                </td>
                <td class="text-center pe-3 fw-bold text-dark"><?= (int)$xl['so_luong'] ?></td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<style>
.px-25 {
    padding-left: 0.65rem !important;
    padding-right: 0.65rem !important;
}
.tracking-wider {
    letter-spacing: 0.03em;
}
/* Hiệu ứng hộp tròn icon nhỏ */
.custom-icon-box {
    width: 38px;
    height: 38px;
    font-size: 1.1rem;
}
/* Bo viền nhẹ nhàng mượt mà cho khối thống kê */
.custom-stat-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.custom-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05) !important;
}
</style>
