<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        /* 🟢 THAY THẾ HOÀN TOÀN: Hiệu ứng Hover/Focus động cho Navbar bằng CSS thuần */
        .custom-nav-link {
            transition: all 0.2s ease-in-out;
            color: var(--bs-body-color);
            font-weight: 500;
        }

        /* Hiệu ứng phủ lớp nền dịu nhẹ (Subtle) khi rê chuột hoặc Tab bàn phím vào Link */
        .custom-nav-link:hover, .custom-nav-link:focus {
            background-color: rgba(63, 81, 181, 0.08) !important;
            color: #3f51b5 !important;
            outline: none;
        }

        /* Biến đổi riêng cho nút Đăng xuất để giữ cảnh báo đỏ nhưng vẫn mượt mà */
        .custom-nav-logout {
            transition: all 0.2s ease-in-out;
        }
        .custom-nav-logout:hover, .custom-nav-logout:focus {
            background-color: rgba(220, 53, 69, 0.08) !important;
            color: #dc3545 !important;
            outline: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top"
     style="backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);">
  <div class="container-xl">
    <a class="navbar-brand fw-bold fs-5 d-flex align-items-center gap-2" href="<?= BASE_URL ?>/home/index" style="color: #2c3e50;">
       <i class="bi bi-mortarboard-fill text-primary"></i> QLSV System
    </a>
    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto gap-1">
        <li class="nav-item">
          <a class="nav-link rounded-3 px-3 custom-nav-link d-flex align-items-center gap-2" href="<?= BASE_URL ?>/home/index">
            <i class="bi bi-house-door"></i> Trang chủ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded-3 px-3 custom-nav-link d-flex align-items-center gap-2" href="<?= BASE_URL ?>/sinhvien/index">
            <i class="bi bi-people"></i> Sinh viên
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded-3 px-3 custom-nav-link d-flex align-items-center gap-2" href="<?= BASE_URL ?>/sinhvien/create">
            <i class="bi bi-person-plus"></i> Thêm SV
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded-3 px-3 custom-nav-link d-flex align-items-center gap-2" href="<?= BASE_URL ?>/thongke/index">
            <i class="bi bi-bar-chart-line"></i> Thống kê
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded-3 px-3 text-danger fw-bold custom-nav-logout d-flex align-items-center gap-2" href="<?= BASE_URL ?>/authen/logout">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

<?php if (isset($_SESSION['flash'])): ?>
<?php
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    
    // Áp dụng bản đồ icon tương ứng với từng trạng thái thông báo
    $flashIcons = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'info'    => 'bi-info-circle-fill',
        'warning' => 'bi-exclamation-circle-fill'
    ];
    $currentIcon = $flashIcons[$flash['type']] ?? 'bi-bell-fill';
?>
<div class="alert alert-<?= htmlspecialchars($flash['type']) ?>-subtle text-<?= htmlspecialchars($flash['type']) ?> border-0 alert-dismissible fade show shadow-sm rounded-3 d-flex align-items-center gap-2 px-3 py-25"
     role="alert">
    <i class="bi <?= $currentIcon ?> fs-5"></i>
    <div class="fw-medium small"><?= htmlspecialchars($flash['msg']) ?></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 11px; top: 50%; transform: translateY(-50%);"></button>
</div>
<?php endif; ?>
