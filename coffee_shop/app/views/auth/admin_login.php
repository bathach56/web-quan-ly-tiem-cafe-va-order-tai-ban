<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống - Coffee Shop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* ========================================= */
        /* 1. CÀI ĐẶT ẢNH NỀN GÁI ANIME Ở ĐÂY        */
        /* ========================================= */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            
            /* Dùng tạm 1 ảnh demo. Bạn hãy tải ảnh gái anime cầm matcha yêu thích, 
               lưu vào thư mục public/assets/img/ và đổi đường dẫn nhé! */
            background-image: url('https://caffecorsini.com/cdn/shop/articles/Header_4880cdaa-2199-4bef-9bfc-cf48126fcea0.png?v=1762180232&width=2000'); 
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* ========================================= */
        /* 2. LỚP PHỦ LÀM MỜ VÀ TỐI ẢNH NỀN          */
        /* ========================================= */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.3); /* Phủ đen 30% để dễ đọc chữ */
            backdrop-filter: blur(6px);     /* Làm mờ background 6px (Mấu chốt là ở đây) */
            z-index: 1;
        }

        /* ========================================= */
        /* 3. HIỆU ỨNG KÍNH MỜ CHO BOX ĐĂNG NHẬP     */
        /* ========================================= */
        .login-box {
            position: relative;
            z-index: 2; /* Nổi lên trên lớp mờ */
            width: 100%;
            max-width: 420px;
            
            /* Nền trắng trong suốt tạo hiệu ứng kính */
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(15px); 
            
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 3rem;
            color: #8d5524;
            margin-bottom: 10px;
        }

        .login-header h4 {
            font-weight: 800;
            color: #333;
            letter-spacing: 1px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 10px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(141, 85, 36, 0.25);
            border-color: #8d5524;
        }

        .btn-login {
            background: #8d5524;
            color: white;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px;
            border: none;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #6b3e18;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(141, 85, 36, 0.4);
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-header">
            <i class="fas fa-coffee"></i>
            <h4>ĐĂNG NHẬP HỆ THỐNG</h4>
            <p class="text-muted mb-0">Quản trị & Thu ngân Coffee Shop</p>
        </div>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger text-center shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-exclamation-circle me-1"></i> <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= URLROOT ?>/auth/login" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" required placeholder="Nhập tài khoản...">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" required placeholder="Nhập mật khẩu...">
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-login fs-5">
                    ĐĂNG NHẬP <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </div>
        </form>
    </div>

</body>
</html>