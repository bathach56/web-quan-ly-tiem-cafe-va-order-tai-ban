<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }} - Quản Trị Hệ Thống</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #d97706; /* Vàng hổ phách */
            --secondary: #b45309; 
            --bg-dark: #0f0e0d; 
            --sidebar-bg: #1c1917; 
            --card-white: #ffffff;
            --text-main: #1c1917;
            --text-gray: #d6d3d1; 
            --border-color: #292524;
            --blue-modal: #0d6efd;
        }

        /* --- LOGIC LIGHT MODE --- */
        [data-theme="light"] {
            --bg-dark: #f5f5f4; 
            --sidebar-bg: #ffffff; 
            --text-gray: #44403c; 
            --border-color: #e7e5e4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-gray);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }

        /* --- TIÊU ĐỀ MÀU VÀNG RỰC RỠ --- */
        h3.fw-bold, .text-warning {
            color: var(--primary) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- BẢNG TRẮNG BO CONG 30PX --- */
        .card, .table-container {
            background-color: var(--card-white) !important;
            border: none !important;
            border-radius: 30px !important;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4) !important;
            margin-bottom: 30px;
            overflow: hidden;
            padding: 10px;
        }

        .table thead th {
            background-color: var(--card-white);
            color: var(--text-main);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 22px 20px;
            border-bottom: 2px solid #f3f2f1;
        }
        .table tbody td {
            padding: 18px 20px;
            color: #44403c;
            vertical-align: middle;
            border-bottom: 1px solid #f8f7f6;
            font-weight: 500;
        }

        /* --- POPUP NỀN TỐI ĐỒNG BỘ --- */
        .modal-content {
            background-color: #262220 !important;
            border: 1px solid #3d3935;
            border-radius: 25px;
            color: white;
        }
        .modal-header { border-bottom: 1px solid #3d3935; padding: 20px 25px; }
        .modal-title { color: var(--blue-modal); font-weight: 800; text-transform: uppercase; }
        .modal-body label { color: #d6d3d1; font-weight: 600; margin-bottom: 8px; font-size: 0.8rem; text-transform: uppercase; }
        .modal-body .form-control, .modal-body .form-select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid #3d3935 !important;
            color: #ffffff !important;
            border-radius: 12px;
            padding: 12px 15px;
            /* Thuộc tính quan trọng để trình duyệt hiểu đây là chế độ tối */
            color-scheme: dark; 
        }

        /* --- LÀM SÁNG DÒNG CHỮ GỢI Ý (PLACEHOLDER) --- */
        .modal-body .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7) !important; /* Màu trắng mờ 70% để đủ sáng nhưng không bị nhầm với chữ đã nhập */
            font-style: italic; /* Nghiêng nhẹ để trông chuyên nghiệp hơn */
            font-size: 0.85rem;
        }

        /* Hỗ trợ cho các trình duyệt khác nhau */
        .modal-body .form-control::-webkit-input-placeholder { color: rgba(255, 255, 255, 0.7); }
        .modal-body .form-control::-moz-placeholder { color: rgba(255, 255, 255, 0.7); }
        .modal-body .form-control:-ms-input-placeholder { color: rgba(255, 255, 255, 0.7); }

        /* Đảm bảo các lựa chọn bên trong có nền tối và chữ trắng */
        .modal-body .form-select option {
            background-color: #262220; /* Khớp với màu nền của Modal */
            color: #ffffff;
        }

        /* Fix lỗi khi hover hoặc focus vào ô chọn */
        .modal-body .form-select:focus {
            border-color: var(--blue-modal) !important;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-save-full {
            width: 100%; background-color: var(--blue-modal); border: none; color: white;
            font-weight: 700; padding: 15px; border-radius: 15px; text-transform: uppercase; transition: 0.3s;
        }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0;
            background: var(--sidebar-bg); border-right: 1px solid var(--border-color); 
            z-index: 1030; overflow-y: auto; transition: 0.3s ease;
        }
        .sidebar-brand {
            padding: 25px 20px; font-weight: 800; color: var(--primary);
            display: flex; align-items: center; text-decoration: none; border-bottom: 1px solid var(--border-color);
        }
        .sidebar a {
            padding: 12px 25px; display: flex; align-items: center; color: var(--text-gray); 
            text-decoration: none; font-size: 0.9rem; transition: 0.2s; border-left: 4px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active { background: rgba(217, 119, 6, 0.1); color: var(--primary); border-left-color: var(--primary); }
        .sidebar a i { width: 25px; font-size: 1.1rem; margin-right: 12px; }

        .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; transition: 0.3s; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }

        /* --- TOPBAR --- */
        .topbar {
            padding: 12px 25px; display: flex; justify-content: flex-end; align-items: center;
            position: sticky; top: 0; background: var(--bg-dark); z-index: 1020;
            border-bottom: 1px solid var(--border-color); backdrop-filter: blur(10px);
        }
        .menu-toggle { display: none; font-size: 1.3rem; cursor: pointer; color: var(--primary); margin-right: auto; }
        @media (max-width: 991.98px) { .menu-toggle { display: block; } }

        .avatar-box {
            width: 38px; height: 38px; border-radius: 10px; font-weight: 800; color: white;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
        }
        .admin-footer { padding: 20px 25px; font-size: 0.75rem; color: #78716c; border-top: 1px solid var(--border-color); margin-top: auto; }
    </style>
</head>
<body data-theme="dark">

    <div class="sidebar-overlay" id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1025;"></div>

    <nav class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            @if(isset($shop_setting) && $shop_setting->logo)
                <img src="{{ asset('img/' . $shop_setting->logo) }}" style="width: 35px; height: 35px; border-radius: 8px; object-fit: cover;" class="me-2">
            @else
                <i class="fa-solid fa-mug-hot me-2"></i>
            @endif
            <span class="text-truncate">{{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</span>
        </a>

        @php $user = auth()->user(); @endphp

        <div class="nav-label" style="padding: 20px 25px 10px; font-size: 0.65rem; color: var(--primary); text-transform: uppercase; font-weight: 700; opacity: 0.8;">Báo cáo & Bán hàng</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Tổng quan
        </a>
        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i> Lịch sử đơn hàng
        </a>

        <div class="nav-label" style="padding: 20px 25px 10px; font-size: 0.65rem; color: var(--primary); text-transform: uppercase; font-weight: 700; opacity: 0.8;">Quản lý thực đơn</div>
        <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="fa-solid fa-mug-saucer"></i> Sản phẩm
        </a>
        <a href="{{ route('categories.index') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> Danh mục món
        </a>
        <a href="{{ route('vouchers.index') }}" class="{{ request()->is('vouchers*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket-simple"></i> Mã giảm giá
        </a>
        <a href="{{ route('tables.index') }}" class="{{ request()->is('tables*') ? 'active' : '' }}">
            <i class="fa-solid fa-couch"></i> Sơ đồ bàn ăn
        </a>

        <div class="nav-label" style="padding: 20px 25px 10px; font-size: 0.65rem; color: var(--primary); text-transform: uppercase; font-weight: 700; opacity: 0.8;">Vận hành & Nhân sự</div>
        <a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i> Đội ngũ nhân viên
        </a>
        <a href="{{ route('settings.index') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
            <i class="fa-solid fa-gears"></i> Cấu hình quán
        </a>
    </nav>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle" id="menuToggle">
                <i class="fa-solid fa-bars-staggered"></i>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- NÚT BẬT TẮT SÁNG TỐI ĐÃ QUAY TRỞ LẠI -->
                <i class="fa-solid fa-moon fs-5" id="theme-toggle" style="cursor:pointer; color: var(--primary);" title="Chuyển chế độ Sáng/Tối"></i>
                
                <div class="dropdown border-start ps-3">
                    <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-bold small user-name-text" style="color: white;">{{ $user->name ?? 'Người dùng' }}</div>
                            <div style="font-size:0.6rem; color: var(--primary); font-weight: 700; text-transform: uppercase;">{{ $user->position ?? 'Quản lý' }}</div>
                        </div>
                        <div class="avatar-box">{{ strtoupper(substr($user->name ?? 'AD', 0, 2)) }}</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 animate__animated animate__fadeIn">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="fa-solid fa-user me-2"></i> Hồ sơ cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger fw-bold border-0 bg-transparent w-100 text-start">
                                    <i class="fa-solid fa-power-off me-2"></i> Thoát hệ thống
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-3 p-md-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 15px; background: #10b981; color: white;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </main>

        <footer class="admin-footer d-flex flex-column flex-md-row justify-content-between align-items-center text-center gap-2">
            <div>&copy; 2026 - {{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</div>
            <div class="fw-bold text-uppercase" style="font-size: 0.7rem;">Nhóm Vua Về Nhì - 23DTHB6 - HUTECH</div>
        </footer>
    </div>

    <script>
        $(document).ready(function() {
            // Sidebar Mobile
            $('#menuToggle, #sidebarOverlay').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').fadeToggle(200);
            });

            // --- LOGIC CHUYỂN ĐỔI GIAO DIỆN SÁNG TỐI ---
            const themeToggle = document.getElementById('theme-toggle');
            const userNameText = document.querySelector('.user-name-text');
            
            // Kiểm tra trạng thái đã lưu trong máy
            if (localStorage.getItem('admin-theme') === 'light') {
                document.body.setAttribute('data-theme', 'light');
                themeToggle.classList.replace('fa-moon', 'fa-sun');
                if(userNameText) userNameText.style.color = '#1c1917';
            }

            themeToggle.addEventListener('click', () => {
                const isDark = document.body.getAttribute('data-theme') !== 'light';
                if (isDark) {
                    document.body.setAttribute('data-theme', 'light');
                    themeToggle.classList.replace('fa-moon', 'fa-sun');
                    if(userNameText) userNameText.style.color = '#1c1917';
                    localStorage.setItem('admin-theme', 'light');
                } else {
                    document.body.setAttribute('data-theme', 'dark');
                    themeToggle.classList.replace('fa-sun', 'fa-moon');
                    if(userNameText) userNameText.style.color = 'white';
                    localStorage.setItem('admin-theme', 'dark');
                }
            });

            $(document).on('show.bs.modal', '.modal', function () {
                $(this).appendTo('body');
            });
        });
    </script>
</body>
</html>