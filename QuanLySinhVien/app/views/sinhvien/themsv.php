<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $monhocs
 * @var string|null $error
 * @var array $danhSachLop
 */
?>

<div class="row justify-content-center">
  <div class="col-12 col-xl-10">

    <div class="card border-0 rounded-4 shadow-sm mb-5 bg-white overflow-hidden">
      
      <div class="card-header border-bottom border-light bg-transparent pt-4 px-4 pb-3">
        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
          <i class="bi bi-person-plus-fill text-indigo fs-4"></i> Thêm Sinh Viên Mới
        </h5>
        <p class="text-secondary mb-0 small-text">Vui lòng điền đầy đủ thông tin cơ bản và điểm số tích lũy của sinh viên.</p>
      </div>
      
      <div class="card-body p-4">

        <?php if(!empty($error)): ?>
        <div class="alert alert-danger-subtle text-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-2.5 px-3" role="alert">
          <i class="bi bi-exclamation-octagon-fill"></i>
          <div class="fw-medium small-text"><?= htmlspecialchars($error) ?></div>
        </div>
        <?php endif; ?>

        <form method="POST"
              action="<?=BASE_URL?>/sinhvien/create"
              enctype="multipart/form-data"
              id="formCreate">

          <div class="row g-4 mb-4">
            
            <div class="col-12 col-md-7 row g-3 m-0 p-0 pe-md-3">
              <div class="col-12">
                <label for="hoten" class="form-label fw-bold small-text text-secondary mb-1">Họ và Tên <span class="text-danger">*</span></label>
                <input type="text" name="hoten" id="hoten" class="form-control rounded-3 custom-input"
                       placeholder="Ví dụ: Nguyễn Văn A"
                       value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>" required>
              </div>
              
              <div class="col-sm-6">
                <label for="mssv" class="form-label fw-bold small-text text-secondary mb-1">Mã Số Sinh Viên (MSSV) <span class="text-danger">*</span></label>
                <input type="text" name="mssv" id="mssv" class="form-control rounded-3 custom-input font-mono"
                       placeholder="Ví dụ: SV012"
                       value="<?= htmlspecialchars($_POST['mssv'] ?? '') ?>" required>
              </div>
              
              <div class="col-sm-6">
                <label for="gioitinh" class="form-label fw-bold small-text text-secondary mb-1">Giới Tính <span class="text-danger">*</span></label>
                <select name="gioitinh" id="gioitinh" class="form-select rounded-3 custom-select" required>
                  <option value="">-- Chọn giới tính --</option>
                  <option value="Nam" <?= (($_POST['gioitinh'] ?? '') === 'Nam') ? 'selected' : '' ?>>Nam</option>
                  <option value="Nữ" <?= (($_POST['gioitinh'] ?? '') === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                </select>
              </div>
              
              <div class="col-12">
                <label for="nganh" class="form-label fw-bold small-text text-secondary mb-1">Chuyên Ngành Học <span class="text-danger">*</span></label>
                <input type="text" name="nganh" id="nganh" class="form-control rounded-3 custom-input"
                       placeholder="Ví dụ: Công nghệ thông tin"
                       value="<?= htmlspecialchars($_POST['nganh'] ?? '') ?>" required>
              </div>

              <div class="col-12">
                <label for="lop_id" class="form-label fw-bold small-text text-secondary mb-1">Lớp học</label>
                <select name="lop_id" id="lop_id" class="form-select rounded-3 custom-select">
                  <option value="">-- Chưa xếp lớp --</option>
                  <?php foreach($danhSachLop as $l): ?>
                  <option value="<?=$l['id']?>">
                    <?=htmlspecialchars($l['ma_lop'])?> — <?=htmlspecialchars($l['nganh'])?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="d-none d-md-block col-md-1 border-start opacity-50 my-2" style="width: 1px; margin-left: 15px; margin-right: 15px;"></div>

            <div class="col-12 col-md-4 row g-3 m-0 p-0 flex-grow-1">
              <div class="col-12">
                <label for="anh_dai_dien" class="form-label fw-bold small-text text-secondary mb-1">Ảnh đại diện</label>
                <input type="file" name="anh_dai_dien" id="anh_dai_dien" class="form-control rounded-3 custom-input"
                       accept="image/jpeg,image/png,image/webp">
                <div class="form-text opacity-70" style="font-size:11px;">Hỗ trợ JPG, PNG, WEBP (Tối đa 2MB)</div>
              </div>

              <div class="col-12">
                <label for="ghi_chu" class="form-label fw-bold small-text text-secondary mb-1">Ghi chú bổ sung</label>
                <textarea name="ghi_chu" id="ghi_chu" class="form-control rounded-3 custom-input" maxlength="100" rows="5"
                          placeholder="Nhập ghi chú đặc biệt về sinh viên nếu có (Tối đa 100 ký tự)..."><?php echo isset($sinhvien['ghi_chu']) ? htmlspecialchars($sinhvien['ghi_chu']) : ''; ?></textarea>
              </div>
            </div>

          </div>

          <div class="border-top border-light pt-4 mb-4">
            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-3">
              <i class="bi bi-journal-check text-secondary"></i> Nhập Điểm Các Môn Học <small class="text-muted fw-normal fs-7">(Tùy chọn)</small>
            </h6>
            
            <div class="table-responsive rounded-3 border bg-white shadow-card">
              <table class="table align-middle mb-0">
                <thead>
                  <tr class="modern-table-header text-secondary text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                    <th class="fw-bold py-3 ps-3">Môn Học</th>
                    <th class="fw-bold py-3 text-center" style="width:120px">CC (10%)</th>
                    <th class="fw-bold py-3 text-center" style="width:120px">GK (30%)</th>
                    <th class="fw-bold py-3 text-center" style="width:120px">CK (60%)</th>
                    <th class="fw-bold py-3 text-center pe-4" style="width:120px">Tổng kết</th>
                  </tr>
                </thead>
                <tbody class="border-top-0">
                  <?php foreach($monhocs as $mh): ?>
                  <tr class="modern-table-row">
                    <td class="ps-3 py-2.5">
                      <span class="badge bg-light text-dark font-mono border border-slate-subtle me-2 px-2 py-1 rounded-2" style="font-weight: 500; font-size:11px;">
                        <?= htmlspecialchars($mh['ma_mon']) ?>
                      </span>
                      <span class="text-dark fw-semibold small-text"><?= htmlspecialchars($mh['ten_mon']) ?></span>
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="10"
                            name="diems[<?= $mh['id'] ?>][cc]"
                            class="form-control form-control-sm text-center rounded-2 custom-table-input font-mono diem-cc"
                            data-id="<?= $mh['id'] ?>" value="0"
                            aria-label="Điểm chuyên cần môn <?= htmlspecialchars($mh['ten_mon']) ?>">
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="10"
                            name="diems[<?= $mh['id'] ?>][gk]"
                            class="form-control form-control-sm text-center rounded-2 custom-table-input font-mono diem-gk"
                            data-id="<?= $mh['id'] ?>" value="0"
                            aria-label="Điểm giữa kỳ môn <?= htmlspecialchars($mh['ten_mon']) ?>">
                    </td>
                    <td>
                      <input type="number" step="0.1" min="0" max="10"
                            name="diems[<?= $mh['id'] ?>][ck]"
                            class="form-control form-control-sm text-center rounded-2 custom-table-input font-mono diem-ck"
                            data-id="<?= $mh['id'] ?>" value="0"
                            aria-label="Điểm cuối kỳ môn <?= htmlspecialchars($mh['ten_mon']) ?>">
                    </td>
                    <td class="text-center pe-4">
                      <span id="tk-<?= $mh['id'] ?>" class="fw-bold font-mono text-rose d-block lh-1" style="font-size: 14px;">0.00</span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-end gap-2 pt-2">
            <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold text-secondary small-text">
              Hủy bỏ
            </a>
            <button type="submit" class="btn btn-brand rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 fw-semibold small-text shadow-none">
              <i class="bi bi-check-circle-fill"></i> Lưu thông tin sinh viên
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
/* CSS Core Utility */
.font-mono { font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace !important; }
.text-indigo { color: #4f46e5 !important; }
.text-emerald { color: #10b981 !important; }
.text-rose { color: #f43f5e !important; }
.small-text { font-size: 13px !important; }
.fs-7 { font-size: 12px !important; }
.py-2\.5 { padding-top: 0.65rem !important; padding-bottom: 0.65rem !important; }
.border-slate-subtle { border-color: #e2e8f0 !important; }

/* Nút Brand Chính phong cách Modern SaaS */
.btn-brand {
    background-color: #4f46e5; border-color: #4f46e5; color: #ffffff;
    transition: all 0.15s ease;
}
.btn-brand:hover { background-color: #4338ca; border-color: #4338ca; color: #ffffff; }

/* Thiết kế Input Form lớn mịn màng */
.custom-input, .custom-select {
    padding: 0.55rem 0.75rem;
    font-size: 13.5px;
    border-color: #cbd5e1;
    color: #1e293b;
    transition: all 0.15s ease-in-out;
}
.custom-input:focus, .custom-select:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
}
.custom-input::placeholder { color: #94a3b8; opacity: 0.8; }

/* Thiết kế Table Input Điểm số gọn gàng */
.shadow-card { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.02) !important; }
.modern-table-header { background-color: #f8fafc !important; }
.modern-table-header th { background-color: transparent !important; border-bottom: 2px solid #f1f5f9 !important; }
.modern-table-row { transition: background-color 0.15s ease; }
.modern-table-row:hover { background-color: #fafafa !important; }

.custom-table-input {
    padding: 0.35rem 0.5rem;
    font-size: 13px;
    border-color: #e2e8f0;
    color: #1e293b;
}
.custom-table-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}
</style>

<script>
// Logic tính Tổng kết tự động cải tiến đổi mã màu CSS chuẩn nhãn số
function tinhTong(id) {
  var cc = parseFloat(document.querySelector('.diem-cc[data-id="'+id+'"]').value) || 0;
  var gk = parseFloat(document.querySelector('.diem-gk[data-id="'+id+'"]').value) || 0;
  var ck = parseFloat(document.querySelector('.diem-ck[data-id="'+id+'"]').value) || 0;
  var tk = (cc*0.1 + gk*0.3 + ck*0.6).toFixed(2);
  var el = document.getElementById('tk-'+id);
  
  el.textContent = tk;
  
  if(parseFloat(tk) >= 5.0) {
      el.className = "fw-bold font-mono text-emerald d-block lh-1";
  } else {
      el.className = "fw-bold font-mono text-rose d-block lh-1";
  }
}

document.querySelectorAll('.diem-cc, .diem-gk, .diem-ck').forEach(function(inp) {
  inp.addEventListener('input', function() {
      tinhTong(this.dataset.id);
  });
});
</script>
