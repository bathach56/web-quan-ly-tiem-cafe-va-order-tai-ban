<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUTECH COFFEE - Đăng ký nhân sự</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { 
            --primary-emerald: #10b981; 
            --secondary-blue: #3b82f6; 
            --bg-dark: #04110e; 
            --input-bg: rgba(255, 255, 255, 0.12); /* Tăng độ sáng để dễ nhìn */
            --input-border: rgba(255, 255, 255, 0.25);
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

        .bg-gradient-overlay { 
            position: absolute; 
            inset: 0; 
            background: radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.15) 0%, transparent 50%); 
            z-index: 0; 
        }

        .register-card { 
            background: rgba(255, 255, 255, 0.07); 
            backdrop-filter: blur(25px); 
            border-radius: 32px; 
            padding: 40px; 
            width: 100%; 
            max-width: 550px; /* Tăng nhẹ chiều rộng để form cân đối hơn */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); 
            z-index: 2; 
            border: 1px solid rgba(255, 255, 255, 0.18); 
        }

        .text-title { color: #ffffff; font-weight: 900; letter-spacing: -0.5px; }
        .form-label { font-size: 0.7rem; font-weight: 800; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; }

        .input-group-text {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--primary-emerald);
            border-radius: 14px 0 0 14px;
            border-right: none;
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-left: none;
            border-radius: 0 14px 14px 0;
            padding: 12px;
            color: white;
            transition: all 0.3s;
        }

        .form-control::placeholder { color: #94a3b8 !important; opacity: 1; }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-emerald);
            background: rgba(255, 255, 255, 0.18);
            color: white;
        }

        /* Password Strength Meter */
        .strength-meter { height: 4px; border-radius: 10px; background: #1e293b; margin-top: 8px; overflow: hidden; display: flex; gap: 4px; }
        .strength-bar { flex: 1; border-radius: 10px; transition: 0.5s; }

        .btn-emerald { 
            background: linear-gradient(to right, #10b981, #3b82f6); 
            color: white; 
            font-weight: 800; 
            border-radius: 14px; 
            padding: 14px; 
            border: none; 
            transition: 0.4s; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
        }

        .btn-emerald:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4); color: white; }
        
        .auth-link { color: var(--primary-emerald); text-decoration: none; font-weight: 700; }
        .match-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-emerald); display: none; z-index: 10; }
    </style>
</head>
<body>
    <div class="bg-gradient-overlay"></div>

    <div class="register-card animate__animated animate__fadeInRight">
        <div class="text-center mb-4">
            <h3 class="text-title mb-1 uppercase italic">Đăng ký<span class="text-emerald-500"> tài khoản</span></h3>
            <p class="text-slate-400 small">Bảo mật siêu an toàn hehehehe</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0 bg-danger bg-opacity-20 text-white mb-4">
                <ul class="mb-0 ps-3"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2">Họ và tên</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-signature"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Trần Phúc Thịnh" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="phucthinh.it" value="{{ old('username') }}" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label mb-2">Địa chỉ Email (Để khôi phục mật khẩu)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="example@hutech.edu.vn" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label mb-2">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••" required onkeyup="checkStrength(this.value)">
                    </div>
                    <div class="strength-meter">
                        <div class="strength-bar" id="bar1"></div>
                        <div class="strength-bar" id="bar2"></div>
                        <div class="strength-bar" id="bar3"></div>
                        <div class="strength-bar" id="bar4"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-2">Xác nhận</label>
                    <div class="input-group position-relative">
                        <span class="input-group-text"><i class="fa-solid fa-circle-check"></i></span>
                        <input type="password" name="password_confirmation" id="confirm" class="form-control" placeholder="••••••" required onkeyup="checkMatch()">
                        <i class="fa-solid fa-check-double match-icon" id="matchIcon"></i>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check small">
                    <input type="checkbox" class="form-check-input" id="terms" required style="accent-color: var(--primary-emerald)">
                    <label class="form-check-label text-slate-400" for="terms">Tôi đồng ý tuân thủ nội quy Hutech Coffee</label>
                </div>
            </div>

            <button type="submit" class="btn btn-emerald w-100 mb-3 shadow">
                HOÀN TẤT ĐĂNG KÝ <i class="fa-solid fa-user-plus ms-2"></i>
            </button>

            <div class="text-center">
                <span class="small text-slate-400">Đã có tài khoản? 
                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
                </span>
            </div>
        </form>
    </div>

    <script>
        function checkStrength(p) {
            const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
            bars.forEach(b => b.style.backgroundColor = '#1e293b');
            let s = 0;
            if(p.length > 5) s++;
            if(/[A-Z]/.test(p)) s++;
            if(/[0-9]/.test(p)) s++;
            if(/[^A-Za-z0-9]/.test(p)) s++;
            const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
            for(let i=0; i<s; i++) bars[i].style.backgroundColor = colors[s-1];
        }

        function checkMatch() {
            const p = document.getElementById('password').value;
            const c = document.getElementById('confirm').value;
            const icon = document.getElementById('matchIcon');
            icon.style.display = (p === c && p !== "") ? 'block' : 'none';
        }
    </script>
</body>
</html>