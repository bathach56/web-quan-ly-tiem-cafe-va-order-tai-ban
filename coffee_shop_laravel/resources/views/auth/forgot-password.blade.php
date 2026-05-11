<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu | HUTECH COFFEE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background-color: #0c0a09; height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; font-family: 'Inter', sans-serif; }
        .forgot-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(25px); border-radius: 32px; padding: 45px; width: 100%; max-width: 420px; border: 1px solid rgba(255, 255, 255, 0.15); }
        .btn-amber { background: #f59e0b; color: white; font-weight: 800; border-radius: 16px; padding: 14px; border: none; transition: 0.3s; }
        .btn-amber:hover { background: #d97706; transform: scale(1.02); }
        .form-control { background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 16px; padding: 12px 20px; }
        .form-control:focus { background: rgba(255, 255, 255, 0.18); color: white; border-color: #f59e0b; box-shadow: none; }
    </style>
</head>
<body>
    <div class="forgot-card animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            <div class="bg-warning d-inline-flex p-3 rounded-4 mb-3 shadow-lg">
                <i class="fa-solid fa-key fa-2x text-dark"></i>
            </div>
            <h3 class="fw-900">QUÊN MẬT KHẨU?</h3>
            <p class="text-stone-400 small">Nhập email để nhận link đặt lại mật khẩu</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 small border-0 bg-success bg-opacity-20 text-white mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="small fw-bold text-stone-300 mb-2 uppercase">Email đăng ký</label>
                <input type="email" name="email" class="form-control" placeholder="example@hutech.edu.vn" required>
            </div>

            <button type="submit" class="btn btn-amber w-100 mb-3">GỬI YÊU CẦU</button>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-warning text-decoration-none small fw-bold">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại đăng nhập
                </a>
            </div>
        </form>
    </div>
</body>
</html>