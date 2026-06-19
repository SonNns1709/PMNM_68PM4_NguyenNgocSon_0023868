<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập — Quản lý Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f7f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 440px;
            margin: 100px auto;
        }
        .login-brand-icon {
            width: 56px;
            height: 56px;
            background-color: rgba(63, 81, 181, 0.08);
            color: #3f51b5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        /* CSS THUẦN: Ép hiệu ứng động cho khung Login thay thế cho inline JavaScript */
        .custom-login-card {
            background: #ffffff;
            transition: transform .2s, box-shadow .2s;
        }
        .custom-login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08) !important;
        }

        /* Khối CSS bổ trợ cho cấu trúc Input Group có chứa Floating Label */
        .input-group .form-floating > .form-control:focus ~ label,
        .input-group .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(.85) translateY(-0.5rem) translateX(0rem);
        }
        .form-control:focus {
            box-shadow: none !important;
        }
        .input-group {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .input-group:focus-within {
            border-color: #3f51b5 !important;
            box-shadow: 0 0 0 0.25rem rgba(63, 81, 181, 0.15);
        }
        .py-25 {
            padding-top: 0.65rem !important;
            padding-bottom: 0.65rem !important;
        }
    </style>
</head>
<body class="login-page">

<div class="container login-container">
    <div class="card border-0 shadow-sm rounded-4 p-3 custom-login-card">
         
        <div class="card-body p-4">
            
            <div class="text-center">
                <div class="login-brand-icon">
                    <i class="bi bi-shield-lock-fill fs-3"></i>
                </div>
                <h3 class="fw-bold mb-1" style="color: #2c3e50;">Quản lý Sinh Viên</h3>
                <p class="text-muted small mb-4">Vui lòng đăng nhập hệ thống để tiếp tục</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger-subtle text-danger border-0 rounded-3 py-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/authen/login">

                <div class="input-group rounded-3 overflow-hidden mb-3 border">
                    <span class="input-group-text bg-light border-0 text-secondary px-3">
                        <i class="bi bi-person"></i>
                    </span>
                    <div class="form-floating flex-grow-1">
                        <input type="text" name="username" id="username"
                               class="form-control border-0 bg-transparent ps-0"
                               placeholder="Username"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               required>
                        <label for="username" class="text-secondary ps-0">Tên đăng nhập</label>
                    </div>
                </div>

                <div class="input-group rounded-3 overflow-hidden mb-4 border">
                    <span class="input-group-text bg-light border-0 text-secondary px-3">
                        <i class="bi bi-key"></i>
                    </span>
                    <div class="form-floating flex-grow-1">
                        <input type="password" name="password" id="password"
                               class="form-control border-0 bg-transparent ps-0"
                               placeholder="Password"
                               required>
                        <label for="password" class="text-secondary ps-0">Mật khẩu</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill w-100 py-25 d-flex align-items-center justify-content-center gap-2 fw-semibold shadow-sm" style="background: #3f51b5; border-color: #3f51b5;">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    Chưa có tài khoản quản trị?
                    <a href="<?= BASE_URL ?>/authen/register" class="text-decoration-none fw-medium" style="color: #ff6f61;">Đăng ký ngay</a>
                </p>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>
