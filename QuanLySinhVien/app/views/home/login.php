<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập — Quản lý Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-color: #4f46e5;       /* Indigo hiện đại */
            --brand-hover: #4338ca;
            --brand-light: rgba(79, 70, 229, 0.06);
            --bg-soft: #f8fafc;           /* Xám xanh dịu mắt */
        }

        body {
            background-color: var(--bg-soft);
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.01em;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        /* Thiết kế Card tối giản, bóng đổ mịn màng */
        .modern-login-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.04) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modern-login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06) !important;
        }

        /* Vùng chứa Icon thương hiệu */
        .login-brand-icon {
            width: 56px;
            height: 56px;
            background-color: var(--brand-light);
            color: var(--brand-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px; /* Bo góc dạng squircle hiện đại hơn hình tròn */
            margin-bottom: 1.25rem;
        }

        /* Thiết kế lại Floating Label kết hợp Icon tinh tế */
        .form-floating-icon {
            position: relative;
        }
        
        .form-floating-icon .form-control {
            padding-left: 2.75rem !important;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        /* Căn chỉnh lại nhãn khi có icon ở trước */
        .form-floating-icon .form-control ~ label {
            padding-left: 2.75rem;
            color: #64748b;
        }

        .form-floating-icon .form-control:focus ~ label,
        .form-floating-icon .form-control:not(:placeholder-shown) ~ label {
            padding-left: 2.75rem;
        }

        /* Vị trí icon đi kèm */
        .form-floating-icon .bi {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            z-index: 5;
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }

        /* Trạng thái Focus sang trọng */
        .form-floating-icon .form-control:focus {
            border-color: var(--brand-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
            background-color: #fff;
        }

        .form-floating-icon .form-control:focus ~ .bi {
            color: var(--brand-color);
        }

        /* Nút bấm Đăng nhập */
        .btn-brand {
            background-color: var(--brand-color);
            border-color: var(--brand-color);
            color: #ffffff;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-brand:hover, .btn-brand:focus {
            background-color: var(--brand-hover);
            border-color: var(--brand-hover);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
        }
    </style>
</head>
<body class="login-page">

<div class="container login-container">
    <div class="card border-0 rounded-4 p-2 p-md-3 modern-login-card">
         
        <div class="card-body p-4">
            
            <div class="text-center">
                <div class="login-brand-icon">
                    <i class="bi bi-shield-lock-fill fs-3"></i>
                </div>
                <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.02em;">Quản lý Sinh Viên</h3>
                <p class="text-muted small mb-4">Vui lòng đăng nhập hệ thống để tiếp tục</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 py-2.5 px-3 mb-4 d-flex align-items-center gap-2" style="font-size: 14px;">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/authen/login">

                <div class="form-floating-icon mb-3">
                    <i class="bi bi-person"></i>
                    <div class="form-floating">
                        <input type="text" name="username" id="username"
                               class="form-control"
                               placeholder="Username"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               required autocomplete="username">
                        <label for="username">Tên đăng nhập</label>
                    </div>
                </div>

                <div class="form-floating-icon mb-4">
                    <i class="bi bi-key"></i>
                    <div class="form-floating">
                        <input type="password" name="password" id="password"
                               class="form-control"
                               placeholder="Password"
                               required autocomplete="current-password">
                        <label for="password">Mật khẩu</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-brand w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-in-right fs-5"></i> Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    Chưa có tài khoản quản trị? 
                    <a href="<?= BASE_URL ?>/authen/register" class="text-decoration-none fw-semibold text-primary ms-1">Đăng ký ngay</a>
                </p>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>
