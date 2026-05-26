<?php
/** @var array $danhSach Khai báo để Intelephense biết biến được truyền từ Controller */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách Sinh Viên</title>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
  <div class="container">
    <span class="navbar-brand">Quản lý Sinh Viên</span>
    <div>
      <a href="<?= BASE_URL ?>/home/index"
         class="btn btn-outline-light btn-sm me-2">Trang chủ</a>
      <a href="<?= BASE_URL ?>/authen/logout"
         class="btn btn-danger btn-sm">Đăng xuất</a>
    </div>
  </div>
</nav>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Danh sách Sinh Viên</h4>
    <span class="badge bg-primary">
      Tổng: <?= count($danhSach) ?> sinh viên
    </span>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover table-bordered mb-0">
        <thead class="table-primary">
          <tr>
            <th>STT</th>
            <th>Họ Tên</th>
            <th>MSSV</th>
            <th>Email</th>
            <th>Điểm</th>
            <th>Xếp loại</th>
            <th>Lớp</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($danhSach)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              Chưa có sinh viên nào.
            </td>
          </tr>
          <?php else: ?>
          <?php foreach ($danhSach as $i => $sv): ?>
          <?php
              $diem = (float) $sv['diem'];
              if ($diem >= 8.5)      { $xl = 'Xuất sắc'; $badge = 'success'; }
              elseif ($diem >= 7.0)  { $xl = 'Giỏi';     $badge = 'primary'; }
              elseif ($diem >= 5.5)  { $xl = 'Khá';      $badge = 'info';    }
              elseif ($diem >= 4.0)  { $xl = 'Trung bình'; $badge = 'warning'; }
              else                   { $xl = 'Yếu';      $badge = 'danger';  }
          ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($sv['ho_ten']) ?></td>
            <td><?= htmlspecialchars($sv['mssv'])   ?></td>
            <td><?= htmlspecialchars($sv['email'])  ?></td>
            <td><?= $sv['diem'] ?></td>
            <td>
              <span class="badge bg-<?= $badge ?>">
                <?= $xl ?>
              </span>
            </td>
            <td><?= htmlspecialchars($sv['ten_lop'] ?? 'Chưa phân lớp') ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
