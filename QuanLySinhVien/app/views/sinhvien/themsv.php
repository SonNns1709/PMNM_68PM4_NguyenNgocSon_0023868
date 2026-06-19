<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $monhocs
 * @var string|null $error
 */
?>

<div class="row justify-content-center">
 <div class="col-lg-9 col-xl-8">

  <div class="card border rounded-4 shadow-sm mb-4 bg-white overflow-hidden">
   
   <div class="card-header border-0 bg-transparent pt-4 px-4 pb-2">
    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
     <i class="bi bi-person-plus-fill text-primary" style="color: #3f51b5 !important;"></i> Thêm Sinh Viên Mới
    </h5>
    <p class="text-secondary small mb-0 mt-1">Vui lòng điền đầy đủ thông tin cơ bản và điểm số tích lũy của sinh viên.</p>
   </div>
   
   <div class="card-body p-4 pt-3">

    <?php if(!empty($error)): ?>
    <div class="alert alert-danger-subtle text-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-4 py-25 px-3" role="alert">
     <i class="bi bi-exclamation-octagon-fill"></i>
     <div><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/sinhvien/create" id="formCreate">

     <div class="row g-3 mb-4">
      <div class="col-md-6">
       <label for="hoten" class="form-label fw-semibold small text-secondary">Họ và Tên <span class="text-danger">*</span></label>
       <input type="text" name="hoten" id="hoten" class="form-control rounded-3 custom-input ps-3"
              placeholder="Ví dụ: Nguyễn Văn A"
              value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>" required>
      </div>
      
      <div class="col-md-6">
       <label for="mssv" class="form-label fw-semibold small text-secondary">Mã Số Sinh Viên (MSSV) <span class="text-danger">*</span></label>
       <input type="text" name="mssv" id="mssv" class="form-control rounded-3 custom-input ps-3"
              placeholder="Ví dụ: SV012"
              value="<?= htmlspecialchars($_POST['mssv'] ?? '') ?>" required>
      </div>
      
      <div class="col-md-6">
       <label for="gioitinh" class="form-label fw-semibold small text-secondary">Giới Tính <span class="text-danger">*</span></label>
       <select name="gioitinh" id="gioitinh" class="form-select rounded-3 custom-select ps-3" required>
        <option value="">-- Chọn giới tính --</option>
        <option value="Nam" <?= (($_POST['gioitinh'] ?? '') === 'Nam') ? 'selected' : '' ?>>Nam</option>
        <option value="Nữ" <?= (($_POST['gioitinh'] ?? '') === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
       </select>
      </div>
      
      <div class="col-md-6">
       <label for="nganh" class="form-label fw-semibold small text-secondary">Chuyên Ngành Học <span class="text-danger">*</span></label>
       <input type="text" name="nganh" id="nganh" class="form-control rounded-3 custom-input ps-3"
              placeholder="Ví dụ: Công nghệ thông tin"
              value="<?= htmlspecialchars($_POST['nganh'] ?? '') ?>" required>
      </div>
     </div>

     <div class="border-top pt-4 mb-4">
      <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-3">
       <i class="bi bi-journal-check text-secondary"></i> Nhập Điểm Các Môn Học <small class="text-muted fw-normal fs-7">(Tùy chọn)</small>
      </h6>
      
      <div class="table-responsive rounded-3 border bg-white">
       <table class="table align-middle table-hover mb-0">
        <thead>
         <tr class="table-light text-secondary small text-uppercase">
          <th class="fw-semibold py-3 ps-3">Môn Học</th>
          <th class="fw-semibold py-3 text-center" style="width:110px">CC (10%)</th>
          <th class="fw-semibold py-3 text-center" style="width:110px">GK (30%)</th>
          <th class="fw-semibold py-3 text-center" style="width:110px">CK (60%)</th>
          <th class="fw-semibold py-3 text-center pe-3" style="width:110px">Tổng kết</th>
         </tr>
        </thead>
        <tbody class="border-top-0">
         <?php foreach($monhocs as $mh): ?>
         <tr>
          <td class="ps-3 py-25">
           <code class="text-dark fw-medium me-1">[<?= htmlspecialchars($mh['ma_mon']) ?>]</code>
           <span class="text-dark fw-medium"><?= htmlspecialchars($mh['ten_mon']) ?></span>
          </td>
          <td>
           <input type="number" step="0.1" min="0" max="10"
                  name="diems[<?= $mh['id'] ?>][cc]"
                  class="form-control form-control-sm text-center rounded-2 custom-table-input diem-cc"
                  data-id="<?= $mh['id'] ?>" value="0"
                  aria-label="Điểm chuyên cần môn <?= htmlspecialchars($mh['ten_mon']) ?>">
          </td>
          <td>
           <input type="number" step="0.1" min="0" max="10"
                  name="diems[<?= $mh['id'] ?>][gk]"
                  class="form-control form-control-sm text-center rounded-2 custom-table-input diem-gk"
                  data-id="<?= $mh['id'] ?>" value="0"
                  aria-label="Điểm giữa kỳ môn <?= htmlspecialchars($mh['ten_mon']) ?>">
          </td>
          <td>
           <input type="number" step="0.1" min="0" max="10"
                  name="diems[<?= $mh['id'] ?>][ck]"
                  class="form-control form-control-sm text-center rounded-2 custom-table-input diem-ck"
                  data-id="<?= $mh['id'] ?>" value="0"
                  aria-label="Điểm cuối kỳ môn <?= htmlspecialchars($mh['ten_mon']) ?>">
          </td>
          <td class="text-center pe-3">
           <span id="tk-<?= $mh['id'] ?>" class="fw-bold text-center d-block lh-1 text-danger" style="font-size: 14px;">0.00</span>
          </td>
         </tr>
         <?php endforeach; ?>
        </tbody>
       </table>
      </div>
     </div>

     <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 fw-medium shadow-sm custom-btn-primary">
       <i class="bi bi-check-circle-fill"></i> Lưu thông tin sinh viên
      </button>
      <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium">
       <i class="bi bi-arrow-left"></i> Quay lại danh sách
      </a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>

<style>
.py-25 {
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
}
.fs-7 {
    font-size: 0.8rem !important;
}
/* Màu nút chính Indigo đồng bộ */
.custom-btn-primary {
    background: #3f51b5 !important;
    border-color: #3f51b5 !important;
    color: #ffffff !important;
}
.custom-btn-primary:hover {
    background: #303f9f !important;
    border-color: #303f9f !important;
}
/* Style xử lý các ô nhập liệu Form lớn */
.custom-input, .custom-select {
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
    border-color: #dee2e6;
    transition: all 0.2s ease-in-out;
}
.custom-input:focus, .custom-select:focus {
    border-color: #3f51b5 !important;
    box-shadow: 0 0 0 0.22rem rgba(63, 81, 181, 0.15) !important;
}
/* Style xử lý các ô nhập điểm nhỏ trong Table */
.custom-table-input {
    padding-top: 0.35rem;
    padding-bottom: 0.35rem;
    border-color: #e2e8f0;
    font-weight: 500;
}
.custom-table-input:focus {
    border-color: #3f51b5 !important;
    box-shadow: 0 0 0 0.15rem rgba(63, 81, 181, 0.12) !important;
}
</style>

<script>
// Logic tính Tổng kết tự động phối mượt mà màu sắc nhãn số hiển thị
function tinhTong(id) {
  var cc = parseFloat(document.querySelector('.diem-cc[data-id="'+id+'"]').value) || 0;
  var gk = parseFloat(document.querySelector('.diem-gk[data-id="'+id+'"]').value) || 0;
  var ck = parseFloat(document.querySelector('.diem-ck[data-id="'+id+'"]').value) || 0;
  var tk = (cc*0.1 + gk*0.3 + ck*0.6).toFixed(2);
  var el = document.getElementById('tk-'+id);
  
  el.textContent = tk;
  el.style.color = parseFloat(tk) >= 5.0 ? '#1d9e75' : '#dc3545';
}

document.querySelectorAll('.diem-cc, .diem-gk, .diem-ck').forEach(function(inp) {
  inp.addEventListener('input', function() {
      tinhTong(this.dataset.id);
  });
});
</script>
