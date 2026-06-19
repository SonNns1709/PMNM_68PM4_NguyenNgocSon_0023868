<div class="text-center py-5 mb-5">
    <h1 class="display-5 fw-bold text-dark mb-3 tracking-tight">
        Quản Lý Sinh Viên
    </h1>
    <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
        Xin chào, <span class="fw-bold text-primary"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></span>!
        Vui lòng chọn một chức năng bên dưới để bắt đầu làm việc.
    </p>
</div>

<div class="row g-4 justify-content-center">
  <?php
  $menus = [
    [
        'icon'  => 'bi-people-fill', // Đổi sang bản -fill để nhìn dày dặn, hiện đại hơn
        'title' => 'Danh Sách SV',
        'sub'   => 'Xem và quản lý toàn bộ sinh viên',
        'url'   => '/sinhvien/index',
        'badge' => 'primary-subtle',
        'text'  => 'primary'
    ],
    [
        'icon'  => 'bi-person-plus-fill',
        'title' => 'Thêm Sinh Viên',
        'sub'   => 'Nhập và lưu trữ thông tin mới',
        'url'   => '/sinhvien/create',
        'badge' => 'success-subtle',
        'text'  => 'success'
    ],
    [
        'icon'  => 'bi-bar-chart-steps',
        'title' => 'Thống Kê',
        'sub'   => 'Báo cáo trực quan & tổng hợp',
        'url'   => '/thongke/index',
        'badge' => 'info-subtle',
        'text'  => 'info'
    ],
    [
        'icon'  => 'bi-box-arrow-right', // Icon logout chuẩn UI phổ biến hơn bi-lightning
        'title' => 'Đăng Xuất',
        'sub'   => 'Thoát an toàn khỏi hệ thống',
        'url'   => '/authen/logout',
        'badge' => 'danger-subtle',
        'text'  => 'danger'
    ],
  ];
  foreach($menus as $m): ?>
  <div class="col-12 col-sm-6 col-lg-3">
    <a href="<?= BASE_URL . $m['url'] ?>" class="text-decoration-none d-block h-100 modern-menu-card">
      <div class="card h-100 border-0 shadow-sm rounded-4 p-3 text-center">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          
          <div class="icon-box d-inline-flex align-items-center justify-content-center rounded-4 mb-4 bg-<?= $m['badge'] ?> text-<?= $m['text'] ?>">
              <i class="bi <?= $m['icon'] ?> fs-3"></i>
          </div>
          
          <h5 class="fw-bold text-dark mb-2 card-title"><?= htmlspecialchars($m['title']) ?></h5>
          <p class="text-secondary small mb-0 card-text"><?= htmlspecialchars($m['sub']) ?></p>
          
        </div>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<style>
/* Bo góc lớn chuẩn xu hướng UI hiện đại (Mobile & Desktop) */
.rounded-4 {
    border-radius: 1rem !important;
}

/* Định dạng kích thước hộp Icon */
.modern-menu-card .icon-box {
    width: 60px;
    height: 60px;
    transition: all 0.3s ease;
}

/* Cấu trúc Card và Hiệu ứng Hover chuẩn Dashboard cao cấp */
.modern-menu-card .card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.03) !important;
}

/* Hiệu ứng mượt mà khi di chuột vào hoặc Focus */
.modern-menu-card:hover .card,
.modern-menu-card:focus-within .card {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
    border-color: rgba(0, 0, 0, 0.08) !important;
}

/* Hiệu ứng tương tác nhẹ lên Icon khi hover card */
.modern-menu-card:hover .icon-box {
    transform: scale(1.1);
}

/* Tối ưu khoảng cách chữ tiêu đề */
.tracking-tight {
    letter-spacing: -0.025em;
}

/* Đảm bảo không bị vỡ viền focus mặc định của trình duyệt nhưng vẫn giữ tính năng Accessibility */
.modern-menu-card:focus {
    outline: none;
}
</style>
