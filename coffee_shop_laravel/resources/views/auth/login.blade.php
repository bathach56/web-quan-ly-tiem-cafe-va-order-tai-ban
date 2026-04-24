<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUTECH COFFEE - Đăng nhập hệ thống</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-amber: #f59e0b; /* Màu hổ phách sáng hơn để tăng tương phản */
            --bg-dark: #0c0a09;
            --input-bg: rgba(255, 255, 255, 0.12);
            --input-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background-color: var(--bg-dark);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            position: relative;
            color: #fff;
        }

        /* Hiệu ứng Background Blobs chuyển động */
        .bg-blob {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(100px);
            z-index: 0;
            border-radius: 50%;
        }

        @keyframes move {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 100px) scale(1.2); }
        }

        /* Card Đăng nhập Glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 40px;
            padding: 55px 45px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8);
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-logo {
            background: var(--primary-amber);
            width: 70px;
            height: 70px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }

        .text-title { color: #ffffff; font-weight: 900; letter-spacing: -1px; font-size: 2rem; }
        .form-label { font-size: 0.75rem; font-weight: 800; color: #d6d3d1; text-transform: uppercase; letter-spacing: 1.5px; }

        /* Tối ưu hóa ô nhập liệu để rõ ràng hơn */
        .input-group-text {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--primary-amber);
            border-radius: 18px 0 0 18px;
            padding-left: 20px;
            border-right: none;
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-left: none;
            border-radius: 0 18px 18px 0;
            padding: 15px;
            color: white;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Làm sáng Placeholder để dễ đọc */
        .form-control::placeholder {
            color: #a8a29e !important;
            opacity: 1;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-amber);
            background: rgba(255, 255, 255, 0.18);
            color: white;
        }

        /* Nút đăng nhập với hiệu ứng Hover */
        .btn-amber {
            background: var(--primary-amber);
            color: white;
            font-weight: 900;
            border-radius: 18px;
            padding: 16px;
            transition: all 0.4s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-amber:hover {
            background: #f97316;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-amber:active { transform: scale(0.98); }

        /* Checkbox & Links */
        .custom-check { 
            width: 18px; 
            height: 18px; 
            accent-color: var(--primary-amber); 
            cursor: pointer;
        }
        .auth-link { color: var(--primary-amber); text-decoration: none; font-weight: 700; transition: 0.3s; }
        .auth-link:hover { color: #fbbf24; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div class="login-card animate__animated animate__fadeInUp">
        <div class="brand-logo">
            <i class="fa-solid fa-mug-hot fa-2x text-white"></i>
        </div>
        
        <div class="text-center mb-5">
            <h1 class="text-title mb-1">HUTECH <span style="color: var(--primary-amber)">COFFEE</span></h1>
            <p class="text-stone-400 small fw-medium">Hệ thống quản trị ca trực - Nhóm 3</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0 bg-danger bg-opacity-20 text-white mb-4 animate__animated animate__shakeX">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" id="loginForm">
            @csrf
            
            <div class="mb-4">
                <label class="form-label mb-2">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user-ninja"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Nhập username của bạn..." value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label mb-2">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-shield-halved"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu hệ thống..." required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5 small">
                <div class="form-check d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input custom-check m-0" id="remember" name="remember">
                    <label class="form-check-label text-stone- stone-400 cursor-pointer" for="remember">Ghi nhớ tôi</label>
                </div>
                <a href="{{ route('password.request') }}" class="auth-link">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-amber w-100 mb-4 shadow-lg" id="loginBtn">
                <span>ĐĂNG NHẬP NGAY</span>
                <i class="fa-solid fa-arrow-right-long animate__animated animate__infinite animate__headShake" style="animation-duration: 2s"></i>
            </button>

            <div class="text-center">
                <p class="small text-stone-500 mb-0">
                    Chưa có tài khoản nhân sự? 
                    <a href="{{ route('register') }}" class="auth-link">Đăng ký tại đây</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').onsubmit = function() {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> ĐANG KIỂM TRA...';
        };
    </script>
</body>
</html>