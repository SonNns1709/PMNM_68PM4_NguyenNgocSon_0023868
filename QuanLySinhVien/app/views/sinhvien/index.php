<?php
/**
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
// Cập nhật hệ thống màu nền tương thích chuẩn Modern Subtle Badge
function getXepLoai(?float $gpa): array {
    if ($gpa === null) {
        return ['text' => 'Chưa có điểm', 'class' => 'bg-secondary-subtle text-secondary', 'icon' => 'bi-dash-circle'];
    }
    
    $thresholds = [
        ['min' => 8.5, 'text' => 'Xuất Sắc',  'class' => 'bg-success-subtle text-success', 'icon' => 'bi-star-fill'],
        ['min' => 7.0, 'text' => 'Giỏi',      'class' => 'bg-primary-subtle text-primary', 'icon' => 'bi-trophy-fill'],
        ['min' => 5.5, 'text' => 'Khá',       'class' => 'bg-info-subtle text-info',       'icon' => 'bi-hand-thumbs-up-fill'],
        ['min' => 4.0, 'text' => 'Trung Bình', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'bi-bar-chart-fill']
    ];

    foreach ($thresholds as $tier) {
        if ($gpa >= $tier['min']) {
            return $tier;
        }
    }

    return ['text' => 'Yếu', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-exclamation-triangle-fill'];
}

// Cải tiến hàm sortLink chèn Icon động mượt mà không lỗi dòng
function sortLink(string $col, string $label, array $filterParams): string {
    $sortBy  = $filterParams['sortBy'] ?? 'id';
    $sortDir = $filterParams['sortDir'] ?? 'ASC';
    
    $isCurrentSort = ($sortBy === $col);
    $isAscending   = (strtoupper($sortDir) === 'ASC');
    
    $newDir = ($isCurrentSort && $isAscending) ? 'DESC' : 'ASC';
    
    $params = [
        'page'    => $filterParams['currentPage'] ?? 1,
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
        $icon = $isAscending
            ? ' <i class="bi bi-arrow-up text-indigo ms-1 align-middle" style="font-size:11px;"></i>'
            : ' <i class="bi bi-arrow-down text-indigo ms-1 align-middle" style="font-size:11px;"></i>';
    } else {
        $icon = ' <i class="bi bi-arrow-down-up text-muted opacity-30 ms-1 align-middle" style="font-size:10px;"></i>';
    }
    
    return '<a href="'.BASE_URL.'/sinhvien/index?'.$qs.'" class="d-inline-flex align-items-center th-sort-link">'.htmlspecialchars($label).$icon.'</a>';
}
?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1 tracking-tight">Hồ Sơ Sinh Viên</h3>
        <p class="text-secondary mb-0 small-text">Quản lý thông tin cơ bản, lớp học và tiến trình điểm số GPA.</p>
    </div>
    <a href="<?= BASE_URL ?>/sinhvien/create" class="btn btn-brand px-4 py-2.5 d-inline-flex align-items-center gap-2 shadow-none">
        <i class="bi bi-person-plus-fill fs-5"></i> Thêm sinh viên mới
    </a>
</div>

<div class="card border-0 rounded-4 filter-card mb-4">
  <div class="card-body p-3 p-md-4">
    <form method="GET" action="<?=BASE_URL?>/sinhvien/index">
      <input type="hidden" name="sortBy"  value="<?=htmlspecialchars($sortBy)?>">
      <input type="hidden" name="sortDir" value="<?=htmlspecialchars($sortDir)?>">
      
      <div class="row g-2">
        <div class="col-12 col-lg-4">
          <div class="position-relative">
            <i class="bi bi-search text-muted position-absolute top-50 start-0 translate-middle-y ms-3 opacity-60"></i>
            <input type="text" name="search" class="form-control ps-5 rounded-3 border-slate"
                   placeholder="Tìm theo tên, MSSV hoặc lớp..." value="<?=htmlspecialchars($search)?>">
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select name="xepLoai" class="form-select rounded-3 border-slate">
            <option value="">-- Hạng lực --</option>
            <option value="xuat_sac"   <?=$xepLoai==='xuat_sac'?'selected':''?>>⭐ Xuất Sắc</option>
            <option value="gioi"       <?=$xepLoai==='gioi'?'selected':''?>>🥇 Giỏi</option>
            <option value="kha"        <?=$xepLoai==='kha'?'selected':''?>>👍 Khá</option>
            <option value="trung_binh" <?=$xepLoai==='trung_binh'?'selected':''?>>📊 Trung Bình</option>
            <option value="yeu"        <?=$xepLoai==='yeu'?'selected':''?>>⚠️ Yếu</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select name="nganh" class="form-select rounded-3 border-slate">
            <option value="">-- Tất cả ngành --</option>
            <?php foreach($danhSachNganh as $ng): ?>
            <option value="<?=htmlspecialchars($ng)?>" <?=$nganh===$ng?'selected':''?>>
              <?=htmlspecialchars($ng)?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select name="lop" class="form-select rounded-3 border-slate">
            <option value="">-- Tất cả lớp --</option>
            <?php foreach($danhSachLop as $l): ?>
            <option value="<?=$l['id']?>" <?=(string)$lop===(string)$l['id']?'selected':''?>>
              <?=htmlspecialchars($l['ma_lop'])?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-6 col-md-12 col-lg-2 d-flex gap-2">
          <button type="submit" class="btn btn-dark w-100 rounded-3 fw-semibold px-3 d-flex align-items-center justify-content-center gap-1.5 button-filter">
             <i class="bi bi-funnel-fill small"></i> Lọc
          </button>
          <?php if(!empty($search)||!empty($xepLoai)||!empty($nganh)||!empty($lop)): ?>
          <a href="<?=BASE_URL?>/sinhvien/index" class="btn btn-light border w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center text-secondary">
             Xóa
          </a>
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3 px-1">
  <div class="text-secondary font-medium" style="font-size:13px">
    <?php if ($totalRecord > 0): ?>
    Hiển thị từ <span class="text-dark fw-bold"><?= $recordStart ?></span> đến <span class="text-dark fw-bold"><?= $recordEnd ?></span> trong <span class="badge bg-light text-dark border font-mono px-2"><?= $totalRecord ?></span> kết quả
    <?php else: ?>
    <span class="text-muted">Không có bản ghi nào được tìm thấy</span>
    <?php endif; ?>
  </div>

  <form method="GET" action="<?=BASE_URL?>/sinhvien/index" class="d-flex align-items-center gap-2 bg-white p-1 rounded-2 border px-2">
    <input type="hidden" name="search"  value="<?=htmlspecialchars($search)?>">
    <input type="hidden" name="xepLoai" value="<?=htmlspecialchars($xepLoai)?>">
    <input type="hidden" name="nganh"   value="<?=htmlspecialchars($nganh)?>">
    <input type="hidden" name="lop"     value="<?=htmlspecialchars($lop)?>">
    <input type="hidden" name="sortBy"  value="<?=htmlspecialchars($sortBy)?>">
    <input type="hidden" name="sortDir" value="<?=htmlspecialchars($sortDir)?>">
    <label for="limit" class="text-secondary small-text fw-medium">Xem:</label>
    <select name="limit" id="limit" class="form-select form-select-sm border-0 bg-transparent p-0 pe-4 shadow-none fw-bold text-dark font-mono" style="width:55px" onchange="this.form.submit()">
      <?php foreach($allowedLimits as $l): ?>
      <option value="<?=$l?>" <?=$limit===$l?'selected':''?>><?=$l?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<div class="table-responsive rounded-4 border bg-white shadow-card mb-4">
  <table class="table table-hover align-middle mb-0">
    <thead>
        <tr class="modern-table-header">
            <th class="fw-bold text-secondary text-uppercase py-3.5 ps-4" style="width: 70px; font-size: 11px; letter-spacing: 0.5px;">STT</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="width: 65px; font-size: 11px; letter-spacing: 0.5px;">Ảnh</th>
            
            <?php
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
            
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="font-size: 11px; letter-spacing: 0.5px;">
                <?= sortLink('hoten', 'Họ Tên', $currentFilters) ?>
            </th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="width: 110px; font-size: 11px; letter-spacing: 0.5px;">Giới Tính</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="width: 130px; font-size: 11px; letter-spacing: 0.5px;">
                <?= sortLink('mssv', 'MSSV', $currentFilters) ?>
            </th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="font-size: 11px; letter-spacing: 0.5px;">Chuyên Ngành</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="font-size: 11px; letter-spacing: 0.5px;">Lớp</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5 text-center" style="width: 85px; font-size: 11px; letter-spacing: 0.5px;">GPA</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="width: 145px; font-size: 11px; letter-spacing: 0.5px;">Xếp Loại</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5" style="font-size: 11px; letter-spacing: 0.5px;">Ghi chú</th>
            <th class="fw-bold text-secondary text-uppercase py-3.5 text-center pe-4" style="width: 145px; font-size: 11px; letter-spacing: 0.5px;">Hành Động</th>
        </tr>
    </thead>
    <tbody class="border-top-0">
      <?php if (empty($sinhviens)): ?>
      <tr>
        <td colspan="11" class="text-center text-muted py-5">
          <div class="py-4">
            <i class="bi bi-person-fill-x fs-1 d-block mb-3 opacity-30 text-indigo"></i>
            <span class="fw-medium text-secondary">
              <?= !empty($search)
                  ? "Không tìm thấy kết quả phù hợp với từ khóa \"" . htmlspecialchars($search) . "\""
                  : "Hệ thống quản lý chưa ghi nhận dữ liệu sinh viên." ?>
            </span>
          </div>
        </td>
      </tr>
      <?php else: ?>
      <?php foreach ($sinhviens as $i => $sv): ?>
      <?php $stt = ($currentPage - 1) * $limit + $i + 1; ?>
      <tr class="modern-table-row">
        <td class="ps-4 py-3 text-secondary font-mono small"><?= sprintf("%02d", $stt) ?></td>
        <td>
            <?php
            $avatarName = !empty($sv['anh_dai_dien']) ? trim($sv['anh_dai_dien']) : '';
            // Đường dẫn kiểm tra file vật lý trên ổ đĩa (Giữ nguyên lùi 3 cấp)
            $diskPath = dirname(__DIR__, 3) . '/public/uploads/sinhviens/' . $avatarName;
            
            if ($avatarName !== '' && file_exists($diskPath)) {
                // SỬA TẠI ĐÂY: Xóa bỏ chữ /public ở đoạn nối chuỗi vì BASE_URL đã có sẵn rồi
                $imgSrc = BASE_URL . '/uploads/sinhviens/' . htmlspecialchars($avatarName);
            ?>
                <img src="<?php echo $imgSrc; ?>" alt="Avatar" class="avatar-circle">
            <?php
            } else {
                // Logic vẽ vòng tròn chữ cái mặc định của bạn (Giữ nguyên)
                $parts    = explode(' ', trim($sv['hoten'] ?? ''));
                $initials = mb_strtoupper(mb_substr(end($parts), 0, 1, 'UTF-8'), 'UTF-8');
                $beautifulColors = ['#4f46e5', '#10b981', '#06b6d4', '#f59e0b', '#f43f5e'];
                $randomColor = $beautifulColors[($sv['id'] ?? 0) % count($beautifulColors)];
            ?>
                <div class="avatar-circle-text" style="background: <?= $randomColor ?>;">
                    <?= $initials ?>
                </div>
            <?php
            }
            ?>
        </td>
        <td class="fw-semibold text-dark-gray text-nowrap"><?= htmlspecialchars($sv['hoten']) ?></td>
        <td>
          <span class="badge px-2 py-1 rounded-2 font-medium border-0 flat-badge <?= $sv['gioitinh'] === 'Nam' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' ?>">
             <i class="bi <?= $sv['gioitinh'] === 'Nam' ? 'bi-gender-male' : 'bi-gender-female' ?> fs-7"></i> <?= htmlspecialchars($sv['gioitinh']) ?>
          </span>
        </td>
        <td><span class="badge bg-light text-dark font-mono border text-uppercase px-2 py-1 rounded-2" style="font-size:12px; font-weight:500;"><?= htmlspecialchars($sv['mssv']) ?></span></td>
        <td><span class="text-secondary fw-medium fs-7 text-nowrap"><?= htmlspecialchars($sv['nganh'] ?? 'Chưa xác định') ?></span></td>
        <td>
          <?php if (!empty($sv['ma_lop'])): ?>
          <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-2 font-medium border-0 flat-badge">
             <?= htmlspecialchars($sv['ma_lop']) ?>
          </span>
          <?php else: ?>
          <span class="badge bg-light text-muted border px-2 py-1 rounded-2 font-medium flat-badge">Trống</span>
          <?php endif; ?>
        </td>
        <?php
            $gpa    = $sv['gpa'] !== null ? (float)$sv['gpa'] : null;
            $xl     = getXepLoai($gpa);
            $gpaClass = ($gpa !== null && $gpa >= 5.0) ? 'text-emerald' : 'text-rose';
        ?>
        <td class="text-center font-mono fw-bold">
            <?php if ($gpa !== null): ?>
            <span class="<?= $gpaClass ?>" style="font-size: 14.5px;"><?= number_format($gpa, 2) ?></span>
            <?php else: ?>
            <span class="text-muted opacity-40">—</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="badge <?= $xl['class'] ?> d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5 rounded-2 fw-semibold flat-badge border-0">
               <i class="<?= $xl['icon'] ?> fs-7"></i> <?= $xl['text'] ?>
            </span>
        </td>
        <td style="max-width: 140px;">
            <div class="text-truncate text-secondary font-medium fs-7" title="<?= htmlspecialchars($sv['ghi_chu'] ?? '') ?>">
                <?= !empty($sv['ghi_chu']) ? htmlspecialchars($sv['ghi_chu']) : '<span class="text-muted opacity-40">—</span>'; ?>
            </div>
        </td>
        <td class="text-center pe-4">
            <div class="d-inline-flex gap-1.5">
                <a href="<?= BASE_URL ?>/sinhvien/diem/<?= $sv['id'] ?>"
                   class="btn btn-action-circle btn-view" title="Xem bảng điểm">
                   <i class="bi bi-card-checklist"></i>
                </a>
                  
                <a href="<?= BASE_URL ?>/sinhvien/edit/<?= $sv['id'] ?>"
                   class="btn btn-action-circle btn-edit" title="Sửa hồ sơ">
                   <i class="bi bi-pencil-fill"></i>
                </a>
                  
                <a href="<?= BASE_URL ?>/sinhvien/delete/<?= $sv['id'] ?>"
                   class="btn btn-action-circle btn-delete" title="Xóa sinh viên"
                   onclick="return confirm('Hệ thống sẽ xóa toàn bộ điểm số của sinh viên này. Bạn chắc chắn muốn xóa <?= htmlspecialchars($sv['hoten']) ?>?')">
                   <i class="bi bi-trash3-fill"></i>
                </a>
            </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php
function pageUrl(int $page, string $search, string $xepLoai, string $nganh, string $lop, string $sortBy, string $sortDir) {
    $params = compact('page', 'search', 'xepLoai', 'nganh', 'lop', 'sortBy', 'sortDir');
    return BASE_URL . '/sinhvien/index?' . http_build_query($params);
}
?>

<?php if ($totalPage > 1): ?>
<div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mt-4 mb-5">
  
  <form method="GET" action="<?= BASE_URL ?>/sinhvien/index" class="d-flex align-items-center gap-2 order-2 order-md-1">
    <input type="hidden" name="search"  value="<?= htmlspecialchars($search) ?>">
    <input type="hidden" name="xepLoai" value="<?= htmlspecialchars($xepLoai) ?>">
    <input type="hidden" name="nganh"   value="<?= htmlspecialchars($nganh) ?>">
    <input type="hidden" name="lop"     value="<?= htmlspecialchars($lop) ?>">
    <input type="hidden" name="sortBy"  value="<?= htmlspecialchars($sortBy) ?>">
    <input type="hidden" name="sortDir" value="<?= htmlspecialchars($sortDir) ?>">
    
    <span class="text-secondary small-text fw-medium">Đến trang:</span>
    <input type="number" name="page" min="1" max="<?= $totalPage ?>" value="<?= $currentPage ?>" class="form-control form-control-sm text-center font-mono rounded-2" style="width: 60px; height:32px;">
    <button type="submit" class="btn btn-sm btn-dark px-2.5 rounded-2 fw-bold" style="height:32px;">Đi</button>
    <span class="text-muted font-mono small-text ms-1">/ <?= $totalPage ?></span>
  </form>

  <nav aria-label="Page navigation" class="order-1 order-md-2">
    <ul class="pagination mb-0 gap-1 border-0">
      <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
        <a class="page-link rounded-3 border bg-white text-secondary px-3 py-1.5 small fw-semibold"
           href="<?= pageUrl($currentPage - 1, $search, $xepLoai, $nganh, $lop, $sortBy, $sortDir) ?>"
           aria-label="Trang trước, chuyển sang trang <?= max(1, $currentPage - 1) ?>">
           <i class="bi bi-chevron-left me-1 fs-7" aria-hidden="true"></i> Trước
        </a>
      </li>

      <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
          <a class="page-link rounded-3 border px-3 py-1.5 fw-bold font-mono mx-05 <?= $i === $currentPage ? 'shadow-none active-page' : 'bg-white text-dark-gray' ?>"
            href="<?= pageUrl($i, $search, $xepLoai, $nganh, $lop, $sortBy, $sortDir) ?>"
            aria-label="Trang <?= $i ?><?= $i === $currentPage ? ', trang hiện tại' : '' ?>">
            <?= $i ?>
          </a>
        </li>
      <?php endfor; ?>

      <li class="page-item <?= $currentPage >= $totalPage ? 'disabled' : '' ?>">
        <a class="page-link rounded-3 border bg-white text-secondary px-3 py-1.5 small fw-semibold"
           href="<?= pageUrl($currentPage + 1, $search, $xepLoai, $nganh, $lop, $sortBy, $sortDir) ?>"
           aria-label="Trang tiếp theo, chuyển sang trang <?= min($totalPage, $currentPage + 1) ?>">
           Tiếp <i class="bi bi-chevron-right ms-1 fs-7" aria-hidden="true"></i>
        </a>
      </li>
    </ul>
  </nav>
</div>
<?php endif; ?>

<style>
.font-mono { font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace !important; }
.text-emerald { color: #10b981 !important; }
.text-rose { color: #f43f5e !important; }
.text-indigo { color: #4f46e5 !important; }
.text-dark-gray { color: #1e293b; }
.small-text { font-size: 13px !important; }
.fs-7 { font-size: 12px !important; }
.tracking-tight { letter-spacing: -0.025em; }

/* Thẻ Filter phẳng mịn màng */
.filter-card {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0 !important;
}
.border-slate { border-color: #cbd5e1 !important; }
.form-control:focus, .form-select:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
}

/* Liên kết sắp xếp cột */
.th-sort-link {
    color: #475569 !important;
    font-weight: 700 !important;
    text-decoration: none;
    transition: color 0.15s ease;
}
.th-sort-link:hover { color: #4f46e5 !important; }

/* Khung cấu trúc ảnh tròn cao cấp */
.avatar-circle {
    width: 34px; height: 34px;
    border-radius: 50%;
    object-fit: cover;
}
.avatar-circle-text {
    width: 34px; height: 34px;
    border-radius: 50%;
    color: #ffffff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
}

/* Định dạng Bảng hiện đại */
.shadow-card { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05), 0 1px 2px -1px rgba(0,0,0,0.05) !important; }
.modern-table-header { background-color: #f8fafc !important; }
.modern-table-header th { background-color: transparent !important; border-bottom: 2px solid #f1f5f9 !important; }
.modern-table-row { transition: background-color 0.15s ease; }
.modern-table-row:hover { background-color: #fafafa !important; }
.flat-badge { font-size: 11.5px !important; letter-spacing: -0.01em; font-weight: 600 !important; }

/* Tránh lỗi hiển thị IDE bằng cách thêm \ trước dấu chấm của Class */
.py-3\.5 { padding-top: 0.85rem !important; padding-bottom: 0.85rem !important; }
.px-2\.5 { padding-left: 0.65rem !important; padding-right: 0.65rem !important; }
.py-1\.5 { padding-top: 0.35rem !important; padding-bottom: 0.35rem !important; }
.gap-1\.5 { gap: 0.375rem !important; }
.mx-05 { margin-left: 0.15rem !important; margin-right: 0.15rem !important; }

/* Nút Action tròn gọn gàng mềm mại */
.btn-action-circle {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    padding: 0; font-size: 13px; border: 1px solid #e2e8f0;
    background: #ffffff; color: #475569;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-view:hover { color: #4f46e5; border-color: #4f46e5; background: rgba(79, 70, 229, 0.05); }
.btn-edit:hover { color: #d97706; border-color: #d97706; background: rgba(217, 119, 6, 0.05); }
.btn-delete:hover { color: #ef4444; border-color: #ef4444; background: rgba(239, 68, 68, 0.05); }

/* Nút Brand chính */
.btn-brand {
    background-color: #4f46e5; border-color: #4f46e5; color: #ffffff;
    border-radius: 8px; font-weight: 600; font-size: 14.5px;
}
.btn-brand:hover { background-color: #4338ca; border-color: #4338ca; color: #ffffff; }

/* Định dạng Phân Trang Active */
.active-page {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
}
.page-link { box-shadow: none !important; }
.page-link:hover:not(.active-page) { background-color: #f1f5f9 !important; color: #1e293b !important; }
</style>
