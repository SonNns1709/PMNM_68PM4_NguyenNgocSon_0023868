<?php
/** @var array $sinhviens Định nghĩa để VS Code biết biến này được truyền từ Controller sang */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">📋 Danh sách Sinh Viên</h4>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-primary fs-6">
            Tổng: <?= count($sinhviens) ?> SV
        </span>
        <a href="<?= BASE_URL ?>/sinhvien/create"
           class="btn btn-success btn-sm">+ Thêm sinh viên</a>
    </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover table-bordered mb-0">
      <thead class="table-primary">
        <tr>
          <th>STT</th><th>Họ Tên</th>
          <th>Giới Tính</th><th>MSSV</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($sinhviens)): ?>
        <tr><td colspan="4" class="text-center text-muted py-4">
            Chưa có sinh viên nào.
        </td></tr>
        <?php else: ?>
        <?php foreach ($sinhviens as $i => $sv): ?>
        <tr>
          <td><?= $i + 1 ?></td>
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
