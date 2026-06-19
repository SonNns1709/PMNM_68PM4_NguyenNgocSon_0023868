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
 * @var string $lop
 * @var array $danhSachLop
 * @var string $sortBy
 * @var string $sortDir
 * @var int $totalRecord
 * @var int $recordStart
 * @var int $recordEnd
 * @var array $allowedLimits
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

<?php
function sortLink(string $col, string $label, array $filterParams): string {
    $sortBy  = $filterParams['sortBy'] ?? 'id';
    $sortDir = $filterParams['sortDir'] ?? 'ASC';
    
    $isCurrentSort = ($sortBy === $col);
    $isAscending   = (strtoupper($sortDir) === 'ASC');
    
    $newDir = ($isCurrentSort && $isAscending) ? 'DESC' : 'ASC';
    
    $params = [
        'page'    => $filterParams['currentPage'] ?? 1, // ĐẢM BẢO: Giữ nguyên số trang đang đứng khi bấm sắp xếp
        'search'  => $filterParams['search'] ?? '',
        'xepLoai' => $filterParams['xepLoai'] ?? '',
        'nganh'   => $filterParams['nganh'] ?? '',
        'lop'     => $filterParams['lop'] ?? '',
        'sortBy'  => $col,
        'sortDir' => $newDir
    ];
    
    $qs = http_build_query($params);
    
    $icon = '';
    if ($isCurrentSort) {
        $icon = $isAscending ? ' ▲' : ' ▼';
    }
    
    return '<a href="'.BASE_URL.'/sinhvien/index?'.$qs.'" class="text-black text-decoration-none">'.htmlspecialchars($label).$icon.'</a>';
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

<form method="GET" action="<?=BASE_URL?>/sinhvien/index" class="mb-3">
  <input type="hidden" name="sortBy"  value="<?=htmlspecialchars($sortBy)?>">
  <input type="hidden" name="sortDir" value="<?=htmlspecialchars($sortDir)?>">
  <div class="input-group flex-wrap" style="max-width:900px">
    <input type="text" name="search" class="form-control"
           placeholder="🔍 Tìm theo họ tên, MSSV hoặc lớp..."
           value="<?=htmlspecialchars($search)?>" style="min-width:200px">

    <select name="xepLoai" class="form-select" style="max-width:150px">
      <option value="">Tất cả</option>
      <option value="xuat_sac"   <?=$xepLoai==='xuat_sac'?'selected':''?>>⭐ Xuất Sắc</option>
      <option value="gioi"       <?=$xepLoai==='gioi'?'selected':''?>>🥇 Giỏi</option>
      <option value="kha"        <?=$xepLoai==='kha'?'selected':''?>>👍 Khá</option>
      <option value="trung_binh" <?=$xepLoai==='trung_binh'?'selected':''?>>📊 TB</option>
      <option value="yeu"        <?=$xepLoai==='yeu'?'selected':''?>>⚠️ Yếu</option>
    </select>

    <select name="nganh" class="form-select" style="max-width:170px">
      <option value="">📚 Tất cả ngành</option>
      <?php foreach($danhSachNganh as $ng): ?>
      <option value="<?=htmlspecialchars($ng)?>" <?=$nganh===$ng?'selected':''?>>
        <?=htmlspecialchars($ng)?>
      </option>
      <?php endforeach; ?>
    </select>

    <select name="lop" class="form-select" style="max-width:170px">
      <option value="">🏫 Tất cả lớp</option>
      <?php foreach($danhSachLop as $l): ?>
      <option value="<?=$l['id']?>" <?=(string)$lop===(string)$l['id']?'selected':''?>>
        <?=htmlspecialchars($l['ma_lop'])?>
      </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary">Lọc</button>
    <?php if(!empty($search)||!empty($xepLoai)||!empty($nganh)||!empty($lop)): ?>
    <a href="<?=BASE_URL?>/sinhvien/index" class="btn btn-outline-secondary">✕ Xoá lọc</a>
    <?php endif; ?>
  </div>
</form>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <div class="text-muted" style="font-size:13px">
    <?php if ($totalRecord > 0): ?>
    Hiển thị <strong><?= $recordStart ?>-<?= $recordEnd ?></strong>
    trong tổng số <strong><?= $totalRecord ?></strong> bản ghi
    <?php else: ?>
    Không có bản ghi nào
    <?php endif; ?>
  </div>

  <form method="GET" action="<?=BASE_URL?>/sinhvien/index" class="d-flex align-items-center gap-2">
    <input type="hidden" name="search"  value="<?=htmlspecialchars($search)?>">
    <input type="hidden" name="xepLoai" value="<?=htmlspecialchars($xepLoai)?>">
    <input type="hidden" name="nganh"   value="<?=htmlspecialchars($nganh)?>">
    <input type="hidden" name="lop"     value="<?=htmlspecialchars($lop)?>">
    <input type="hidden" name="sortBy"  value="<?=htmlspecialchars($sortBy)?>">
    <input type="hidden" name="sortDir" value="<?=htmlspecialchars($sortDir)?>">
    <label for="limit" class="text-muted" style="font-size:13px">Hiển thị:</label>
    <select name="limit" class="form-select form-select-sm" style="width:75px"
            onchange="this.form.submit()">
      <?php foreach($allowedLimits as $l): ?>
      <option value="<?=$l?>" <?=$limit===$l?'selected':''?>><?=$l?></option>
      <?php endforeach; ?>
    </select>
    <span class="text-muted" style="font-size:13px">/ trang</span>
  </form>
</div>

<div class="table-responsive rounded-4 border bg-white shadow-sm mb-4">
  <table class="table table-hover align-middle mb-0">
    <thead>
        <tr class="table-active">
            <th class="fw-semibold py-3 ps-4" style="width: 70px">STT</th>
            <th class="fw-semibold py-3" style="width: 70px">Ảnh</th>
            
            <?php
            // Chuẩn bị một mảng filter an toàn chứa đầy đủ trạng thái hiện tại để truyền vào sortLink
            $currentFilters = [
                'search'  => $search ?? '',
                'xepLoai' => $xepLoai ?? '',
                'nganh'   => $nganh ?? '',
                'lop'     => $lop ?? '',
                'sortBy'  => $sortBy ?? 'id',
                'sortDir' => $sortDir ?? 'ASC',
                'currentPage' => $currentPage ?? 1
            ];
            ?>
            
            <th class="fw-semibold py-3">
                <?= sortLink('hoten', 'Họ Tên', $currentFilters) ?>
            </th>
            
            <th class="fw-semibold py-3" style="width: 120px">Giới Tính</th>
            
            <th class="fw-semibold py-3" style="width: 140px">
                <?= sortLink('mssv', 'MSSV', $currentFilters) ?>
            </th>
            
            <th class="fw-semibold py-3">Chuyên Ngành</th>
            <th class="fw-semibold py-3">Lớp Học</th>
            <th class="fw-semibold py-3 text-center" style="width: 90px">GPA</th>
            <th class="fw-semibold py-3" style="width: 150px">Xếp Loại</th>
            <th class="fw-semibold py-3">Ghi chú</th>
            <th class="fw-semibold py-3 text-center pe-4" style="width: 150px">Hành Động</th>
        </tr>
        </thead>
    <tbody class="border-top-0">
      <?php if (empty($sinhviens)): ?>
      <tr>
        <td colspan="11" class="text-center text-muted py-5">
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
        <td>
            <?php if (isset($sv['anh_dai_dien']) && trim($sv['anh_dai_dien']) !== ''): ?>
                <img src="<?=BASE_URL?>/public/uploads/sinhviens/<?=htmlspecialchars($sv['anh_dai_dien'])?>"
                    alt="Ảnh <?=htmlspecialchars($sv['hoten'])?>"
                    style="width:36px; height:36px; border-radius:50%; object-fit:cover; border: 2px solid #000000;">
            <?php else: ?>
                <?php
                    $parts    = explode(' ', trim($sv['hoten']));
                    $initials = mb_strtoupper(mb_substr(end($parts), 0, 1, 'UTF-8'), 'UTF-8');
                    
                    // Sử dụng ID sinh viên để tạo màu cố định không đổi khi F5
                    $beautifulColors = [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                        '#6f42c1', '#fd7e14', '#e83e8c', '#20c997', '#5a5c69'
                    ];
                    $randomColor = $beautifulColors[$sv['id'] % count($beautifulColors)];
                ?>
                <div style="width:36px; height:36px; border-radius:50%; background:<?= $randomColor ?>;
                            color:#fff; display:flex; align-items:center; justify-content:center;
                            font-size:14px; font-weight:600;">
                    <?= $initials ?>
                </div>
            <?php endif; ?>
        </td>
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
        <td>
          <?php if (!empty($sv['ma_lop'])): ?>
          <span class="badge rounded-pill bg-primary-subtle text-primary px-25 py-1 fw-medium">
            <?= htmlspecialchars($sv['ma_lop']) ?>
          </span>
          <?php else: ?>
          <span class="badge rounded-pill bg-secondary-subtle text-secondary px-25 py-1 fw-medium">Chưa xếp lớp</span>
          <?php endif; ?>
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
        <td style="max-width: 150px;">
        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($sv['ghi_chu']); ?>">
            <?php echo !empty($sv['ghi_chu']) ? htmlspecialchars($sv['ghi_chu']) : '—'; ?>
        </div>
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
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>&lop=<?= urlencode($lop) ?>&sortBy=<?= urlencode($sortBy) ?>&sortDir=<?= urlencode($sortDir) ?>">
         <i class="bi bi-chevron-left small"></i> Trước
      </a>
    </li>

    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
      <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
        <a class="page-link rounded-3 border-0 px-3 fw-medium mx-05 <?= $i === $currentPage ? 'shadow-sm' : 'bg-light text-dark' ?>"
          style="<?= $i === $currentPage ? 'background:#3f51b5 !important; color:#ffffff !important;' : '' ?>"
          href="<?= BASE_URL ?>/sinhvien/index?page=<?= $i ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>&lop=<?= urlencode($lop) ?>&sortBy=<?= urlencode($sortBy) ?>&sortDir=<?= urlencode($sortDir) ?>"
          aria-label="Trang <?= $i ?>" <?= $i === $currentPage ? 'aria-current="page"' : '' ?>>
          <?= $i ?>
        </a>
      </li>
    <?php endfor; ?>

    <li class="page-item <?= $currentPage >= $totalPage ? 'disabled' : '' ?>">
      <a class="page-link rounded-3 border-0 bg-light text-secondary px-3"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&xepLoai=<?= urlencode($xepLoai) ?>&nganh=<?= urlencode($nganh) ?>&lop=<?= urlencode($lop) ?>&sortBy=<?= urlencode($sortBy) ?>&sortDir=<?= urlencode($sortDir) ?>">
         Tiếp <i class="bi bi-chevron-right small"></i>
      </a>
    </li>

  </ul>
</nav>
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
