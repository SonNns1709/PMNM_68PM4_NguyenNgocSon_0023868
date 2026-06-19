<?php
/** * Khai báo biến để Intelephense nhận diện dữ liệu truyền từ Controller sang
 * @var array $sinhvien
 * @var array $monhocs
 * @var array $diemMap
 */
?>

<div class="row justify-content-center">
 <div class="col-lg-9">
  <div class="card border-0 shadow-sm rounded-4 mb-4 custom-edit-card"
       style="background: #ffffff; transition: transform .2s, box-shadow .2s;">
       
   <div class="card-body p-4 p-md-5">
    
    <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
        <div class="p-2 bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-pencil-square fs-4"></i>
        </div>
        <div>
            <h4 class="fw-bold text-dark mb-0">Cập Nhật Sinh Viên</h4>
            <span class="text-secondary small fw-medium">MSSV: <?= htmlspecialchars($sinhvien['mssv']) ?></span>
        </div>
    </div>

    <?php if(!empty($error)): ?>
    <div class="alert alert-danger-subtle text-danger border-0 rounded-3 py-2 px-3 mb-4 d-flex align-items-center gap-2" style="font-size: 14px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/sinhvien/edit/<?= $sinhvien['id'] ?>">

     <h6 class="fw-bold text-secondary mb-3 d-flex align-items-center gap-2">
         <i class="bi bi-person-lines-fill"></i> Thông tin cơ bản
     </h6>
     
     <div class="row g-3 mb-4">
      <div class="col-md-6">
       <div class="form-floating">
        <input type="text" name="hoten" id="hoten" class="form-control rounded-3"
               placeholder=" " value="<?= htmlspecialchars($sinhvien['hoten']) ?>" required>
        <label for="hoten">Họ và Tên <span class="text-danger">*</span></label>
       </div>
      </div>
      
      <div class="col-md-6">
       <div class="form-floating">
        <input type="text" name="mssv" id="mssv" class="form-control rounded-3"
               placeholder=" " value="<?= htmlspecialchars($sinhvien['mssv']) ?>" required>
        <label for="mssv">Mã số sinh viên <span class="text-danger">*</span></label>
       </div>
      </div>
      
      <div class="col-md-6">
       <div class="form-floating">
        <select name="gioitinh" id="gioitinh" class="form-select rounded-3" required>
         <option value="Nam" <?= $sinhvien['gioitinh'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
         <option value="Nữ"  <?= $sinhvien['gioitinh'] === 'Nữ'  ? 'selected' : '' ?>>Nữ</option>
        </select>
        <label for="gioitinh">Giới Tính <span class="text-danger">*</span></label>
       </div>
      </div>
      
      <div class="col-md-6">
       <div class="form-floating">
        <input type="text" name="nganh" id="nganh" class="form-control rounded-3"
               placeholder=" " value="<?= htmlspecialchars($sinhvien['nganh'] ?? '') ?>" required>
        <label for="nganh">Ngành Học <span class="text-danger">*</span></label>
       </div>
      </div>
     </div>

     <hr class="text-black-50 my-4">
     
     <h6 class="fw-bold text-secondary mb-3 d-flex align-items-center gap-2">
         <i class="bi bi-calculator"></i> Cập Nhật Điểm Các Môn
     </h6>
     
     <div class="table-responsive rounded-4 border bg-white shadow-sm mb-4">
      <table class="table table-hover align-middle mb-0">
       <thead>
        <tr class="table-active">
         <th class="fw-semibold py-3 ps-4">Môn Học</th>
         <th class="fw-semibold py-3 text-center" style="width:110px">CC (10%)</th>
         <th class="fw-semibold py-3 text-center" style="width:110px">GK (30%)</th>
         <th class="fw-semibold py-3 text-center" style="width:110px">CK (60%)</th>
         <th class="fw-semibold py-3 text-center pe-4" style="width:110px">Tổng Kết</th>
        </tr>
       </thead>
       <tbody class="border-top-0">
       <?php foreach($monhocs as $mh):
         // Khởi tạo và trích xuất điểm từ bản đồ điểm có sẵn
         $d  = $diemMap[$mh['id']] ?? null;
         $cc = $d ? $d['diem_chuyen_can'] : 0;
         $gk = $d ? $d['diem_giua_ky']    : 0;
         $ck = $d ? $d['diem_cuoi_ky']    : 0;
         $tk = $d ? $d['diem_tong_ket']   : 0;
         $tkColor = (float)$tk >= 5 ? '#1d9e75' : '#dc3545';
       ?>
       <tr>
        <td class="ps-4 py-3">
            <code class="me-1"><?= $mh['ma_mon'] ?></code>
            <span class="fw-medium text-dark"><?= htmlspecialchars($mh['ten_mon']) ?></span>
        </td>
        <td>
            <input type="number" step="0.1" min="0" max="10"
                   name="diems[<?= $mh['id'] ?>][cc]"
                   class="form-control text-center rounded-3 diem-cc"
                   data-id="<?= $mh['id'] ?>" value="<?= $cc ?>">
        </td>
        <td>
            <input type="number" step="0.1" min="0" max="10"
                   name="diems[<?= $mh['id'] ?>][gk]"
                   class="form-control text-center rounded-3 diem-gk"
                   data-id="<?= $mh['id'] ?>" value="<?= $gk ?>">
        </td>
        <td>
            <input type="number" step="0.1" min="0" max="10"
                   name="diems[<?= $mh['id'] ?>][ck]"
                   class="form-control text-center rounded-3 diem-ck"
                   data-id="<?= $mh['id'] ?>" value="<?= $ck ?>">
        </td>
        <td class="pe-4 text-center">
            <span id="tk-<?= $mh['id'] ?>" class="fw-bold fs-6 d-block" style="color: <?= $tkColor ?>;">
                <?= number_format((float)$tk, 1) ?>
            </span>
        </td>
       </tr>
       <?php endforeach; ?>
       </tbody>
      </table>
     </div>

     <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 fw-medium shadow-sm" style="background:#3f51b5; border-color:#3f51b5;">
        <i class="bi bi-check-lg"></i> Lưu thay đổi
      </button>
      <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">
        ← Quay lại
      </a>
     </div>
    </form>
    
   </div>
  </div>
 </div>
</div>

<style>
.custom-edit-card:hover {
    box-shadow: 0 12px 30px rgba(0, 0, 0, .06) !important;
}
/* Giúp các ô nhập điểm nhỏ gọn, tập trung hơn khi click */
.table .form-control {
    padding: 0.375rem 0.5rem;
    font-size: 14px;
}
.table .form-control:focus {
    border-color: #3f51b5 !important;
    box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.15) !important;
}
</style>

<script>
function tinhTong(id){
  var cc = parseFloat(document.querySelector('.diem-cc[data-id="'+id+'"]').value) || 0;
  var gk = parseFloat(document.querySelector('.diem-gk[data-id="'+id+'"]').value) || 0;
  var ck = parseFloat(document.querySelector('.diem-ck[data-id="'+id+'"]').value) || 0;
  var tk = (cc * 0.1 + gk * 0.3 + ck * 0.6).toFixed(2);
  
  var el = document.getElementById('tk-' + id);
  el.textContent = parseFloat(tk).toFixed(1); // Đồng bộ 1 chữ số thập phân cho gọn gàng
  el.style.color = parseFloat(tk) >= 5 ? '#1d9e75' : '#dc3545';
}
document.querySelectorAll('.diem-cc, .diem-gk, .diem-ck').forEach(function(inp){
  inp.addEventListener('input', function(){ tinhTong(this.dataset.id); });
});
</script>
