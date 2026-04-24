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
            --primary: #d97706; 
            --secondary: #b45309; 
            --bg-dark: #272320;
            --sidebar-bg: #1c1917; 
            --card-bg: #2f2a26; 
            --text-gray: #d6d3d1; 
            --border-color: #44403c;
        }

        [data-theme="light"] {
            --bg-dark: #fdf8f5; 
            --sidebar-bg: #ffffff; 
            --card-bg: #ffffff;
            --text-gray: #57534e; 
            --border-color: #e7e5e4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-gray);
            transition: all 0.3s ease;
            margin: 0;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px; 
            height: 100vh; 
            position: fixed;
            background: var(--sidebar-bg); 
            border-right: 1px solid var(--border-color); 
            z-index: 1050;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        @media (max-width: 991.98px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0 !important; }
        }

        .sidebar-brand {
            padding: 30px 20px; 
            font-weight: 800; 
            color: var(--primary);
            font-size: 1.2rem; 
            display: flex; 
            align-items: center; 
            text-decoration: none;
            text-transform: uppercase;
        }

        .nav-label {
            padding: 20px 25px 10px; 
            font-size: 0.65rem; 
            color: var(--primary);
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            font-weight: 700; 
            opacity: 0.85;
        }

        .sidebar a {
            padding: 13px 25px; 
            display: flex; 
            align-items: center; 
            color: var(--text-gray);
            text-decoration: none; 
            font-size: 0.9rem; 
            font-weight: 500; 
            transition: 0.2s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(217, 119, 6, 0.08); 
            color: var(--primary); 
            border-right: 4px solid var(--primary);
        }

        .sidebar a i { width: 30px; font-size: 1.1rem; margin-right: 12px; text-align: center; }

        /* --- MAIN CONTENT --- */
        .main-content { 
            margin-left: 260px; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        /* --- TOPBAR: Tối ưu áp sát góc phải cho 16:9 --- */
        .topbar {
            padding: 12px 20px; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center;
            position: sticky; 
            top: 0; 
            background: var(--bg-dark); 
            z-index: 1020;
            border-bottom: 1px solid var(--border-color); 
            backdrop-filter: blur(10px);
        }

        @media (max-width: 991.98px) {
            .topbar { justify-content: space-between; }
        }

        .menu-toggle {
            display: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--primary);
        }

        @media (max-width: 991.98px) {
            .menu-toggle { display: block; }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-overlay.active { display: block; }

        .avatar-box {
            width: 38px; height: 38px; border-radius: 10px; 
            font-weight: 800; color: white;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 8px rgba(217, 119, 6, 0.2);
        }

        .admin-footer { padding: 20px 25px; font-size: 0.75rem; color: #78716c; border-top: 1px solid var(--border-color); margin-top: auto; }
    </style>
</head>
<body data-theme="dark">

    <div id="page-loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 3px; background: var(--primary); z-index: 9999; display: none;"></div>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar shadow-lg" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            @if(isset($shop_setting) && $shop_setting->logo && file_exists(public_path('img/' . $shop_setting->logo)))
                <img src="{{ asset('img/' . $shop_setting->logo) }}" 
                     style="width: 35px; height: 35px; border-radius: 8px;" 
                     class="me-2 shadow-sm">
            @else
                <i class="fa-solid fa-mug-hot me-2"></i>
            @endif
            <span class="text-truncate">{{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</span>
        </a>

        @php 
            $user = auth()->user();
            $isAdmin = $user && $user->position === 'Admin';
        @endphp

        <div class="nav-label">Hệ Thống</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Tổng quan
        </a>

        @if(!$isAdmin)
        <a href="{{ route('pos.index') }}" class="{{ request()->is('pos*') ? 'active' : '' }}">
            <i class="fa-solid fa-cash-register"></i> Máy bán hàng (POS)
        </a>
        @endif

        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i> Báo cáo doanh thu
        </a>

        @if($isAdmin)
            <div class="nav-label">Quản Lý Thực Đơn</div>
            <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="fa-solid fa-mug-hot"></i> Sản phẩm
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-ul"></i> Danh mục
            </a>
            <a href="{{ route('tables.index') }}" class="{{ request()->is('tables*') ? 'active' : '' }}">
                <i class="fa-solid fa-couch"></i> Danh sách bàn
            </a>

            <div class="nav-label">Vận Hành & Nhân Sự</div>
            <a href="{{ route('inventory.index') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i> Kho nguyên liệu
            </a>
            <a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-group"></i> Nhân sự
            </a>
            <a href="{{ route('settings.index') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i> Cấu hình
            </a>
        @endif
    </div>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle" id="menuToggle">
                <i class="fa-solid fa-bars-staggered"></i>
            </div>

            <div class="d-flex align-items-center gap-3 animate__animated animate__fadeIn">
                <i class="fa-solid fa-moon" id="theme-toggle" style="font-size: 1.1rem; cursor: pointer;" title="Đổi giao diện"></i>
                
                <div class="dropdown border-start ps-3">
                    <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-bold small">{{ $user->name ?? 'Người dùng' }}</div>
                            <div style="font-size:0.65rem; color: var(--primary); font-weight: 700; text-transform: uppercase;">
                                {{ $user->position ?? 'Quản trị' }}
                            </div>
                        </div>
                        <div class="avatar-box shadow-sm">
                            {{ substr($user->name ?? 'AD', 0, 2) }}
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 animate__animated animate__fadeIn">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="fa-solid fa-user-gear me-2"></i> Hồ sơ cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger fw-bold border-0 bg-transparent w-100 text-start">
                                    <i class="fa-solid fa-power-off me-2"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-3 p-md-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </main>

        <footer class="admin-footer d-flex flex-column flex-md-row justify-content-between align-items-center text-center gap-2">
            <div>&copy; 2026 - {{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</div>
            <div class="fw-bold text-uppercase" style="font-size: 0.7rem;">Đồ án Nhóm 3 - 23DTHB6 - HUTECH</div>
        </footer>
    </div>

    <script>
        $(document).ready(function() {
            // Sidebar Mobile Toggle
            $('#menuToggle, #sidebarOverlay').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });

            // Theme Toggle (Dark/Light)
            const themeToggle = document.getElementById('theme-toggle');
            const body = document.body;
            if (localStorage.getItem('admin-theme') === 'light') {
                body.setAttribute('data-theme', 'light');
                themeToggle.classList.replace('fa-moon', 'fa-sun');
            }
            themeToggle.addEventListener('click', () => {
                const isDark = body.getAttribute('data-theme') === 'dark';
                body.setAttribute('data-theme', isDark ? 'light' : 'dark');
                themeToggle.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
                localStorage.setItem('admin-theme', isDark ? 'light' : 'dark');
            });

            // Page Loader
            window.addEventListener('beforeunload', () => { 
                document.getElementById('page-loader').style.display = 'block'; 
            });

            // Modal Bootstrap Fix
            $(document).on('show.bs.modal', '.modal', function () { $(this).appendTo('body'); });
        });
    </script>
</body>
</html>