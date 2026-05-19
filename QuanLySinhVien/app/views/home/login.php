<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập — Quản lý Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f2f5; }
        .login-card {
            max-width: 420px;
            margin: 80px auto;
            border-radius: 12px;
            border: 1px solid #dee2e6;
        }
        .login-title { font-size: 22px; font-weight: 500; }
    </style>
</head>
<body>
<div class="card login-card shadow-sm p-4">
    <div class="card-body">
        <h2 class="login-title text-center mb-1">Quản lý Sinh Viên</h2>
        <p class="text-center text-muted mb-4" style="font-size:14px">
            Vui lòng đăng nhập để tiếp tục
        </p>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2" style="font-size:14px">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/authen/login">

            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" id="username" class="form-control"
                    placeholder="Nhập username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="font-size:15px; font-weight:500;">
                Đăng nhập
            </button>
        </form>

        <p class="text-center text-muted mt-3" style="font-size:13px">
            Chưa có tài khoản?
            <a href="<?= BASE_URL ?>/authen/register" class="text-decoration-none">Đăng ký</a>
        </p>
    </div>
</div>
</body>
</html>



