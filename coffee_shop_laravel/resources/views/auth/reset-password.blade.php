<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu | HUTECH COFFEE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0c0a09; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; color: #fff; }
        .reset-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(25px); border-radius: 32px; padding: 45px; width: 100%; max-width: 420px; border: 1px solid rgba(255, 255, 255, 0.15); }
        .btn-amber { background: #f59e0b; color: white; font-weight: 800; border-radius: 16px; padding: 14px; border: none; }
        .form-control { background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 16px; padding: 12px 20px; }
        .form-control:focus { background: rgba(255, 255, 255, 0.18); color: white; border-color: #f59e0b; box-shadow: none; }
    </style>
</head>
<body>
    <div class="reset-card">
        <h4 class="text-center fw-900 mb-4 text-warning">ĐẶT LẠI MẬT KHẨU</h4>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-3">
                <label class="small fw-bold text-stone-300">Email của bạn</label>
                <input type="email" name="email" class="form-control" placeholder="example@hutech.edu.vn" required>
            </div>

            <div class="mb-3">
                <label class="small fw-bold text-stone-300">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control" placeholder="••••••" required>
            </div>

            <div class="mb-4">
                <label class="small fw-bold text-stone-300">Xác nhận mật khẩu mới</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••" required>
            </div>

            <button type="submit" class="btn btn-amber w-100">CẬP NHẬT MẬT KHẨU</button>
        </form>
    </div>
</body>
</html>