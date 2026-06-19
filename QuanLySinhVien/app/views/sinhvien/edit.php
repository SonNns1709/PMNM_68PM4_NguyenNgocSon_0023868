<?php
/**
 * @var array $sinhvien
 * @var array $monhocs
 * @var array $diemMap
 * @var string|null $error
 * @var array $danhSachLop
 */
?>

<div class="row justify-content-center">
 <div class="col-lg-10 col-xl-9">
  <div class="card border-0 rounded-4 mb-5 modern-edit-card">
       
   <div class="card-body p-4 p-md-5">
    
    <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-4">
        <div class="p-2 bg-indigo-subtle text-indigo rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-pencil-square fs-4"></i>
        </div>
        <div>
            <h4 class="fw-bold text-dark mb-1 tracking-tight">Cập Nhật Hồ Sơ Sinh Viên</h4>
            <span class="badge bg-light text-secondary border font-mono px-2 py-1" style="font-size: 12px;">MSSV: <?= htmlspecialchars($sinhvien['mssv']) ?></span>
        </div>
    </div>

    <?php if(!empty($error)): ?>
    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 py-2.5 px-3 mb-4 d-flex align-items-center gap-2" style="font-size: 14px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div class="fw-medium"><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?=BASE_URL?>/sinhvien/edit/<?=$sinhvien['id']?>" enctype="multipart/form-data">

      <h6 class="text-uppercase tracking-wider text-secondary fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 11px;">
          <i class="bi bi-person-lines-fill text-indigo fs-6"></i> Thông tin cơ bản
      </h6>
      
      <div class="row g-3 mb-4">
       <div class="col-md-6">
        <div class="form-floating">
         <input type="text" name="hoten" id="hoten" class="form-control rounded-3"
                placeholder=" " value="<?= htmlspecialchars($sinhvien['hoten']) ?>" required>
         <label for="hoten" class="text-secondary">Họ và Tên <span class="text-danger">*</span></label>
        </div>
       </div>
       
       <div class="col-md-6">
        <div class="form-floating">
         <input type="text" name="mssv" id="mssv" class="form-control rounded-3 font-mono"
                placeholder=" " value="<?= htmlspecialchars($sinhvien['mssv']) ?>" required>
         <label for="mssv" class="text-secondary">Mã số sinh viên <span class="text-danger">*</span></label>
        </div>
       </div>
       
       <div class="col-md-6">
        <div class="form-floating">
         <select name="gioitinh" id="gioitinh" class="form-select rounded-3" required>
          <option value="Nam" <?= $sinhvien['gioitinh'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
          <option value="Nữ"  <?= $sinhvien['gioitinh'] === 'Nữ'  ? 'selected' : '' ?>>Nữ</option>
         </select>
         <label for="gioitinh" class="text-secondary">Giới Tính <span class="text-danger">*</span></label>
        </div>
       </div>
       
       <div class="col-md-6">
        <div class="form-floating">
         <input type="text" name="nganh" id="nganh" class="form-control rounded-3"
                placeholder=" " value="<?= htmlspecialchars($sinhvien['nganh'] ?? '') ?>" required>
         <label for="nganh" class="text-secondary">Ngành Học <span class="text-danger">*</span></label>
        </div>
       </div>

       <div class="col-md-6">
        <div class="form-floating">
         <select name="lop_id" id="lop_id" class="form-select rounded-3">
            <option value="">-- Chưa xếp lớp --</option>
            <?php foreach($danhSachLop as $l): ?>
            <option value="<?=$l['id']?>" <?=(string)($sinhvien['lop_id']??'')===(string)$l['id']?'selected':''?>>
                <?=htmlspecialchars($l['ma_lop'])?> — <?=htmlspecialchars($l['nganh'])?>
            </option>
            <?php endforeach; ?>
         </select>
         <label for="lop_id" class="text-secondary">Lớp học trực thuộc</label>
        </div>
       </div>

       <div class="col-md-6">
        <div class="d-flex align-items-center gap-3 h-100 border rounded-3 p-2 bg-light-subtle">
            <?php if (!empty($sinhvien['anh_dai_dien'])): ?>
                <img src="<?=BASE_URL?>/public/uploads/sinhviens/<?=htmlspecialchars($sinhvien['anh_dai_dien'])?>"
                     alt="Avatar" class="avatar-preview flex-shrink-0">
            <?php else: ?>
                <div class="avatar-preview-empty flex-shrink-0 d-flex align-items-center justify-content-center bg-secondary-subtle">
                    <i class="bi bi-person text-secondary"></i>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <input type="file" name="anh_dai_dien" id="anh" class="form-control form-control-sm border-0 bg-transparent p-0 shadow-none" accept="image/jpeg,image/png,image/webp">
                <div class="text-muted" style="font-size:11px;">Giữ trống nếu không đổi ảnh</div>
            </div>
        </div>
       </div>
      </div>

      <div class="mb-4">
        <div class="form-floating">
          <textarea name="ghi_chu" id="ghi_chu" class="form-control rounded-3" style="height: 100px" placeholder=" " maxlength="100"><?= isset($sinhvien['ghi_chu']) ? htmlspecialchars($sinhvien['ghi_chu']) : ''; ?></textarea>
          <label for="ghi_chu" class="text-secondary">Ghi chú (Tối đa 100 ký tự)</label>
        </div>
      </div>

      <hr class="border-light-dark my-45">
      
      <h6 class="text-uppercase tracking-wider text-secondary fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 11px;">
          <i class="bi bi-calculator text-indigo fs-6"></i> Cập nhật điểm các môn
      </h6>
      
      <div class="table-responsive rounded-4 border bg-white shadow-sm mb-4">
       <table class="table table-hover align-middle mb-0">
        <thead>
         <tr class="modern-table-header">
          <th class="fw-bold text-secondary text-uppercase py-3.5 ps-4" style="font-size: 11px; letter-spacing: 0.5px;">Môn Học</th>
          <th class="fw-bold text-secondary text-uppercase py-3.5 text-center" style="width:120px; font-size: 11px; letter-spacing: 0.5px;">CC (10%)</th>
          <th class="fw-bold text-secondary text-uppercase py-3.5 text-center" style="width:120px; font-size: 11px; letter-spacing: 0.5px;">GK (30%)</th>
          <th class="fw-bold text-secondary text-uppercase py-3.5 text-center" style="width:120px; font-size: 11px; letter-spacing: 0.5px;">CK (60%)</th>
          <th class="fw-bold text-secondary text-uppercase py-3.5 text-center pe-4" style="width:120px; font-size: 11px; letter-spacing: 0.5px;">Tổng Kết</th>
         </tr>
        </thead>
        <tbody class="border-top-0">
        <?php foreach($monhocs as $mh):
          $d   = $diemMap[$mh['id']] ?? null;
          $cc = $d ? $d['diem_chuyen_can'] : 0;
          $gk = $d ? $d['diem_giua_ky']    : 0;
          $ck = $d ? $d['diem_cuoi_ky']    : 0;
          $tk = $d ? $d['diem_tong_ket']   : 0;
          $tkClass = (float)$tk >= 5 ? 'text-emerald' : 'text-rose';
        ?>
        <tr class="modern-table-row">
         <td class="ps-4 py-3">
             <span class="badge bg-light text-dark font-mono border text-uppercase px-2 py-1 rounded-2 me-2" style="font-size: 11px;"><?= $mh['ma_mon'] ?></span>
             <span class="fw-semibold text-dark-gray" style="font-size: 14px;"><?= htmlspecialchars($mh['ten_mon']) ?></span>
         </td>
         <td>
             <input type="number" step="0.1" min="0" max="10"
                    name="diems[<?= $mh['id'] ?>][cc]"
                    class="form-control text-center rounded-2 font-mono grade-input diem-cc"
                    data-id="<?= $mh['id'] ?>" value="<?= $cc ?>">
         </td>
         <td>
             <input type="number" step="0.1" min="0" max="10"
                    name="diems[<?= $mh['id'] ?>][gk]"
                    class="form-control text-center rounded-2 font-mono grade-input diem-gk"
                    data-id="<?= $mh['id'] ?>" value="<?= $gk ?>">
         </td>
         <td>
             <input type="number" step="0.1" min="0" max="10"
                    name="diems[<?= $mh['id'] ?>][ck]"
                    class="form-control text-center rounded-2 font-mono grade-input diem-ck"
                    data-id="<?= $mh['id'] ?>" value="<?= $ck ?>">
         </td>
         <td class="pe-4 text-center">
             <span id="tk-<?= $mh['id'] ?>" class="fw-bold fs-6 d-block font-mono <?= $tkClass ?>">
                 <?= number_format((float)$tk, 1) ?>
             </span>
         </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
       </table>
      </div>

      <div class="d-flex align-items-center gap-2 mt-4 pt-2">
       <button type="submit" class="btn btn-brand px-4 py-2 d-flex align-items-center gap-2">
         <i class="bi bi-cloud-arrow-up-fill fs-5"></i> Lưu thay đổi
       </button>
       <a href="<?= BASE_URL ?>/sinhvien/index" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold text-secondary">
         Hủy bỏ
       </a>
      </div>
    </form>
    
   </div>
  </div>
 </div>
</div>

<style>
/* Font định dạng chuyên sâu cho số liệu */
.font-mono {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace !important;
}
.text-emerald { color: #10b981 !important; }
.text-rose { color: #f43f5e !important; }
.text-indigo { color: #4f46e5 !important; }
.bg-indigo-subtle { background-color: rgba(79, 70, 229, 0.08) !important; }
.border-light-dark { border-color: #e2e8f0 !important; }
.text-dark-gray { color: #1e293b; }

/* Thay thế dấu chấm bằng \. để IDE không báo lỗi */
.my-45 { margin-top: 1.75rem !important; margin-bottom: 1.75rem !important; }

/* Thẻ Form phẳng chuyên nghiệp */
.modern-edit-card {
    background: #ffffff;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01) !important;
}

/* Định dạng cấu trúc Floating Labels mịn */
.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: var(--text-indigo);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
}

/* Khối xem trước avatar */
.avatar-preview, .avatar-preview-empty {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px dashed #cbd5e1;
}
.avatar-preview-empty i {
    font-size: 20px;
}

/* Định dạng bảng điểm */
.modern-table-header {
    background-color: #f8fafc !important;
}
.modern-table-header th {
    background-color: transparent !important;
    border-bottom: 2px solid #f1f5f9 !important;
}
.modern-table-row:hover {
    background-color: #fdfdfd !important;
}

/* Khung Input Điểm */
.grade-input {
    padding: 0.4rem 0.5rem !important;
    font-size: 14px;
    border: 1px solid #cbd5e1;
    max-width: 90px;
    margin: 0 auto;
}
.grade-input:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
}

/* Sửa lỗi cú pháp tại đây: Thêm dấu \ trước dấu chấm của class */
.py-3\.5 {
    padding-top: 0.85rem !important;
    padding-bottom: 0.85rem !important;
}

.px-2\.5 {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}

.py-1\.5 {
    padding-top: 0.375rem !important;
    padding-bottom: 0.375rem !important;
}

.gap-1\.5 {
    gap: 0.375rem !important;
}

/* Nút Submit thương hiệu */
.btn-brand {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: #ffffff;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}
.btn-brand:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
}
.tracking-wider { letter-spacing: 0.05em; }
.tracking-tight { letter-spacing: -0.025em; }
</style>
