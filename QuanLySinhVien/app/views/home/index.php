<div class="text-center py-5 mb-4">
    <h1 class="display-5 fw-extrabold mb-2" style="color: var(--lp-primary); letter-spacing: -0.5px;">
        Quản Lý Sinh Viên
    </h1>
    <p class="text-secondary fs-5">
        Xin chào, <span class="fw-semibold" style="color: var(--lp-coral)">
        <?= htmlspecialchars($user['name'] ?? 'Admin') ?></span>!
        Chọn chức năng bên dưới để bắt đầu làm việc.
    </p>
</div>

<div class="row g-4 justify-content-center">
  <?php
  // Đổi các icon emoji cũ sang class tên của Bootstrap Icons (bi-) để đồng bộ cấu trúc UI hiện đại
  $menus = [
    [
        'icon'  => 'bi-people',
        'title' => 'Danh Sách SV',
        'sub'   => 'Xem toàn bộ sinh viên',
        'url'   => '/sinhvien/index',
        'badge' => 'primary-subtle',
        'text'  => 'primary'
    ],
    [
        'icon'  => 'bi-person-plus',
        'title' => 'Thêm Sinh Viên',
        'sub'   => 'Nhập thông tin mới',
        'url'   => '/sinhvien/create',
        'badge' => 'success-subtle',
        'text'  => 'success'
    ],
    [
        'icon'  => 'bi-bar-chart-line',
        'title' => 'Thống Kê',
        'sub'   => 'Báo cáo tổng hợp',
        'url'   => '/thongke/index',
        'badge' => 'info-subtle',
        'text'  => 'info'
    ],
    [
        'icon'  => 'bi-lightning',
        'title' => 'Đăng Xuất',
        'sub'   => 'Thoát khỏi hệ thống',
        'url'   => '/authen/logout',
        'badge' => 'danger-subtle',
        'text'  => 'danger'
    ],
  ];
  foreach($menus as $m): ?>
  <div class="col-6 col-md-3">
    <a href="<?= BASE_URL . $m['url'] ?>" class="text-decoration-none d-block h-100 custom-menu-card">
      <div class="card-body text-center p-4">
        <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3 bg-<?= $m['badge'] ?> text-<?= $m['text'] ?>"
             style="width: 64px; height: 64px;">
            <i class="bi <?= $m['icon'] ?> fs-2"></i>
        </div>
        
        <h5 class="fw-semibold mb-1" style="color: var(--lp-text);"><?= htmlspecialchars($m['title']) ?></h5>
        <p class="text-muted small mb-0"><?= htmlspecialchars($m['sub']) ?></p>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<style>
.custom-menu-card {
    transition: transform .2s, box-shadow .2s;
}
/* Hiệu ứng mượt mà khi di chuột vào (hover) hoặc khi dùng bàn phím để Focus vào thẻ <a> */
.custom-menu-card:hover, .custom-menu-card:focus {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(233, 69, 96, .3);
    outline: none; /* Ẩn viền mặc định khi focus để giữ thẩm mỹ */
}

/* Đảm bảo chữ to và đậm nét hơn cho tiêu đề trang chủ */
.fw-extrabold {
    font-weight: 800 !important;
}
/* Tránh đường viền focus xấu xí của trình duyệt làm mất thẩm mỹ form bo tròn */
a:focus {
    outline: none !important;
}
</style>
