<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $sinhvien
 */
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">

      <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">✏️ Cập Nhật Sinh Viên
          <small class="text-muted">— MSSV: <?= htmlspecialchars($sinhvien['mssv']) ?></small>
        </h5>
      </div>

      <div class="card-body">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2" style="font-size:14px">
          <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST"
              action="<?= BASE_URL ?>/sinhvien/edit/<?= $sinhvien['id'] ?>">

          <div class="mb-3">
            <label for="hoten" class="form-label fw-bold">Họ và Tên</label>
            <input type="text"
                   name="hoten"
                   class="form-control"
                   value="<?= htmlspecialchars($sinhvien['hoten']) ?>"
                   required>
          </div>

          <div class="mb-3">
            <label for="gioitinh" class="form-label fw-bold">Giới Tính</label>
            <select name="gioitinh" class="form-select" required>
              <option value="Nam"
                <?= $sinhvien['gioitinh']==='Nam' ? 'selected' : '' ?>>Nam</option>
              <option value="Nữ"
                <?= $sinhvien['gioitinh']==='Nữ'  ? 'selected' : '' ?>>Nữ</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="mssv" class="form-label fw-bold">MSSV</label>
            <input type="text"
                   name="mssv"
                   class="form-control"
                   value="<?= htmlspecialchars($sinhvien['mssv']) ?>"
                   required>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">
              💾 Lưu thay đổi
            </button>
            <a href="<?= BASE_URL ?>/sinhvien/index"
               class="btn btn-secondary">← Quay lại</a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
