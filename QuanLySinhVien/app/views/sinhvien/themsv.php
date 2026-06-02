<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">➕ Thêm Sinh Viên Mới</h5>
      </div>
      <div class="card-body">

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2" style="font-size:14px">
          <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/sinhvien/create">

          <div class="mb-3">
            <label for="hoten" class="form-label fw-bold">Họ và Tên</label>
            <input type="text" id="hoten" name="hoten" class="form-control"
                   placeholder="Nhập họ và tên đầy đủ"
                   value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>"
                   required>
          </div>

          <div class="mb-3">
            <label for="gioitinh" class="form-label fw-bold">Giới Tính</label>
            <select id="gioitinh" name="gioitinh" class="form-select" required>
              <option value="">-- Chọn giới tính --</option>
              <option value="Nam">Nam</option>
              <option value="Nữ">Nữ</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="mssv" class="form-label fw-bold">MSSV</label>
            <input type="text" id="mssv" name="mssv" class="form-control"
                   placeholder="Ví dụ: SV006"
                   value="<?= htmlspecialchars($_POST['mssv'] ?? '') ?>"
                   required>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
              ✅ Thêm sinh viên
            </button>
            <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-secondary">← Quay lại</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>