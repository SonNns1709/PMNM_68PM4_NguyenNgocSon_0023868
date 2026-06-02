<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Sinh Viên</title>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold"
       href="<?= BASE_URL ?>/home/index">📚 Quản lý Sinh Viên</a>
    <div class="navbar-nav ms-auto flex-row gap-3">
      <a class="nav-link text-white"
         href="<?= BASE_URL ?>/sinhvien/index">Danh sách SV</a>
      <a class="nav-link text-white"
         href="<?= BASE_URL ?>/sinhvien/create">+ Thêm SV</a>
      <a class="nav-link text-warning fw-bold"
         href="<?= BASE_URL ?>/authen/logout">Đăng xuất</a>
    </div>
  </div>
</nav>

<div class="container mt-4">