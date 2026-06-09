<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $sinhviens
 * @var string $search
 * @var int $currentPage
 * @var int $limit
 * @var int $totalPage
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">📋 Danh sách Sinh Viên</h4>
    <a href="<?= BASE_URL ?>/sinhvien/create" class="btn btn-success btn-sm">+ Thêm sinh viên</a>
</div>

<form method="GET" action="<?= BASE_URL ?>/sinhvien/index" class="mb-3">
    <input type="hidden" name="url" value="sinhvien/index">
    <div class="input-group" style="max-width:420px">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="🔍 Tìm theo họ tên hoặc MSSV..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Tìm</button>
        <?php if (!empty($search)): ?>
        <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-outline-secondary" aria-label="Xóa tìm kiếm">Xoá</a>
        <?php endif; ?>
    </div>
</form>

<div class="card shadow-sm mb-3">
  <div class="card-body p-0">
    <table class="table table-hover table-bordered mb-0">
      <thead class="table-primary">
        <tr>
          <th>STT</th>
          <th>Họ Tên</th>
          <th>Giới Tính</th>
          <th>MSSV</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($sinhviens)): ?>
        <tr>
          <td colspan="4" class="text-center text-muted py-4">
            <?= !empty($search)
                ? "Không tìm thấy sinh viên phù hợp với \"" . htmlspecialchars($search) . "\""
                : "Chưa có sinh viên nào." ?>
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($sinhviens as $i => $sv): ?>
        <?php $stt = ($currentPage - 1) * $limit + $i + 1; ?>
        <tr>
          <td><?= $stt ?></td>
          <td><?= htmlspecialchars($sv['hoten']) ?></td>
          <td>
            <span class="badge <?= $sv['gioitinh']==='Nam'?'bg-info':'bg-danger' ?>">
              <?= htmlspecialchars($sv['gioitinh']) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($sv['mssv']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($totalPage > 1): ?>
<nav>
  <ul class="pagination pagination-sm justify-content-center flex-wrap">

    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
      <a class="page-link"
         aria-label="Trước (Quay lại trang cũ)"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage-1 ?>&search=<?= urlencode($search) ?>">
        <span aria-hidden="true">←</span> Trước
      </a>
    </li>

    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
      <a class="page-link"
         aria-label="Trang <?= $i ?>"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $i ?>&search=<?= urlencode($search) ?>">
        <?= $i ?>
      </a>
    </li>
    <?php endfor; ?>

    <li class="page-item <?= $currentPage >= $totalPage ? 'disabled' : '' ?>">
      <a class="page-link"
         aria-label="Tiếp (Chuyển sang trang mới)"
         href="<?= BASE_URL ?>/sinhvien/index?page=<?= $currentPage+1 ?>&search=<?= urlencode($search) ?>">
        Tiếp <span aria-hidden="true">→</span>
      </a>
    </li>

  </ul>
</nav>

<p class="text-center text-muted" style="font-size:13px">
    Trang <?= $currentPage ?> / <?= $totalPage ?>
    <?php if (!empty($search)): ?>
    — Kết quả tìm kiếm cho: <strong><?= htmlspecialchars($search) ?></strong>
    <?php endif; ?>
</p>
<?php endif; ?>
