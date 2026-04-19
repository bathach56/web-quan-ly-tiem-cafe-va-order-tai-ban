<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Premium Coffee</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            /* Ảnh nền ly cafe chất lượng cao */
            background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        /* Lớp phủ làm tối ảnh nền, giúp nổi bật khung đăng nhập */
        .login-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Khung đăng nhập */
        .login-card {
            background-color: #f6f2ee; /* Màu kem nhẹ */
            border-radius: 16px;
            padding: 40px 35px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            z-index: 2; /* Đẩy khung lên trên lớp phủ */
            position: relative;
        }

        .text-coffee { color: #784821; } /* Màu nâu cafe cho Icon */
        .text-title { color: #2c1e16; font-weight: 800; letter-spacing: 0.5px; }

        /* Nút đăng nhập */
        .btn-coffee {
            background-color: #935d2d;
            color: white;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-coffee:hover {
            background-color: #784821;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(147, 93, 45, 0.4);
        }

        /* Style cho ô nhập liệu */
        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
        }
        .input-group-text {
            background-color: #ffffff;
            border-right: none;
            color: #a09a90;
            border-radius: 8px 0 0 8px;
            border-color: #e5e0da;
        }
        .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
            border-color: #e5e0da;
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #e5e0da;
        }
        .form-control::placeholder { color: #bbb; }
    </style>
</head>
<body>

    <div class="login-overlay"></div>

    <div class="login-card text-center">
        <i class="fa-solid fa-mug-hot fa-2x text-coffee mb-3"></i>
        <h5 class="text-title mb-1">ĐĂNG NHẬP HỆ THỐNG</h5>
        <p class="text-secondary mb-4" style="font-size: 0.75rem;">Quản trị & Thu ngân Coffee Shop</p>

        @if(session('error'))
            <div class="alert alert-danger py-2 small border-0" style="background-color: #fee2e2; color: #dc2626; border-radius: 8px;">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0 text-start" style="background-color: #fee2e2; color: #dc2626; border-radius: 8px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="mb-3 text-start">
                <label class="form-label">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tài khoản..." value="{{ old('username') }}" autocomplete="off">
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 10px;"></small>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." autocomplete="off">
                </div>
                 <small class="text-muted mt-1 d-block" style="font-size: 10px;"></small>
            </div>

            <button type="submit" class="btn btn-coffee w-100 py-2 mt-2">
                ĐĂNG NHẬP <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>
        </form>

        <!-- Chỉ thêm phần này -->
        <div class="text-center mt-3">
            <small class="text-muted">Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-coffee fw-bold">Đăng ký ngay</a>
            </small>
        </div>
    </div>

</body>
</html>