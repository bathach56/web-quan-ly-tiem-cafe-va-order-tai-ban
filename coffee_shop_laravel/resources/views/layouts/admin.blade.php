<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $shop_setting->shop_name ?? 'Hutech Coffee' }} - Hệ Thống Quản Trị</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #d97706; --secondary: #b45309; --bg-dark: #272320;
            --sidebar-bg: #1c1917; --card-bg: #2f2a26; --text-gray: #d6d3d1; --border-color: #44403c;
        }

        [data-theme="light"] {
            --bg-dark: #fdf8f5; --sidebar-bg: #ffffff; --card-bg: #ffffff;
            --text-gray: #57534e; --border-color: #e7e5e4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-gray);
            transition: all 0.3s ease;
            margin: 0;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px; height: 100vh; position: fixed;
            background: var(--sidebar-bg); border-right: 1px solid var(--border-color); z-index: 1000;
        }

        .sidebar-brand {
            padding: 30px 20px; font-weight: 800; color: var(--primary);
            font-size: 1.2rem; display: flex; align-items: center; cursor: pointer;
            text-decoration: none;
        }

        .nav-label {
            padding: 20px 25px 10px; font-size: 0.65rem; color: var(--primary);
            text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; opacity: 0.85;
        }

        .sidebar a {
            padding: 13px 25px; display: flex; align-items: center; color: var(--text-gray);
            text-decoration: none; font-size: 0.93rem; font-weight: 500; transition: 0.2s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(217, 119, 6, 0.08); color: var(--primary); border-right: 3px solid var(--primary);
        }

        .sidebar a i { width: 30px; font-size: 1.15rem; margin-right: 14px; text-align: center; }

        .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            padding: 15px 35px; display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; background: var(--bg-dark); z-index: 999;
            border-bottom: 1px solid var(--border-color); backdrop-filter: blur(10px);
        }

        .search-box {
            background: var(--card-bg); border: 1px solid var(--border-color);
            border-radius: 12px; color: var(--text-gray); padding: 10px 15px; width: 350px; font-size: 0.85rem;
        }

        .avatar-box {
            width: 38px; height: 38px; border-radius: 8px; font-weight: 700; color: white;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
        }

        #page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 3px; background: var(--primary); z-index: 9999; display: none; }
    </style>
</head>
<body data-theme="dark">

    <div id="page-loader"></div>

    <div class="sidebar shadow">
        <a href="{{ auth()->user()->position === 'Admin' ? route('dashboard') : route('pos.index') }}" class="sidebar-brand animate__animated animate__fadeIn">
            @if(isset($shop_setting) && $shop_setting->logo)
                <img src="{{ asset('img/' . $shop_setting->logo) }}" style="width: 35px; height: 35px; border-radius: 8px;" class="me-2 shadow-sm">
            @else
                <i class="fa-solid fa-mug-hot me-2"></i>
            @endif
            <span class="text-truncate">{{ $shop_setting->shop_name ?? 'Hutech Coffee' }}</span>
        </a>

        @php 
            $user = auth()->user();
            $isAdmin = $user && $user->position === 'Admin';
        @endphp

        {{-- CHỈ HIỆN PHẦN PHỤC VỤ CHO NHÂN VIÊN (STAFF) --}}
        @if(!$isAdmin)
            <div class="nav-label animate__animated animate__fadeIn">Phục Vụ</div>
            <a href="{{ route('pos.index') }}" class="{{ request()->is('pos*') ? 'active' : '' }}">
                <i class="fa-solid fa-cash-register"></i> Máy POS
            </a>
            <a href="{{ route('tables.floor_plan') }}" class="{{ request()->is('tables/floor-plan') ? 'active' : '' }}">
                <i class="fa-solid fa-map"></i> Sơ đồ bàn
            </a>
        @endif

        {{-- HIỆN MENU QUẢN TRỊ CHO ADMIN --}}
        @if($isAdmin)
            <div class="nav-label animate__animated animate__fadeIn">Hệ Thống</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Tổng quan
            </a>
            <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Báo cáo doanh thu
            </a>

            <div class="nav-label">Quản Lý</div>
            <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> Thực đơn
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Danh mục
            </a>
            <a href="{{ route('tables.index') }}" class="{{ request()->is('tables') ? 'active' : '' }}">
                <i class="fa-solid fa-couch"></i> Danh sách bàn
            </a>

            <div class="nav-label">Nội Bộ</div>
            <a href="{{ route('inventory.index') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fa-solid fa-warehouse"></i> Kho hàng
            </a>
            <a href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> Nhân sự
            </a>
            <a href="{{ route('settings.index') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Cấu hình
            </a>
        @endif
    </div>

    <div class="main-content">
        <header class="topbar">
            <div class="position-relative animate__animated animate__fadeInDown">
                <i class="fa fa-search position-absolute text-secondary" style="top:12px; left:15px;"></i>
                <input type="text" class="search-box ps-5" placeholder="Tìm kiếm nhanh...">
            </div>

            <div class="d-flex align-items-center gap-4 animate__animated animate__fadeInDown">
                <i class="fa-solid fa-moon" id="theme-toggle" style="font-size: 1.2rem; cursor: pointer;" title="Đổi giao diện"></i>
                
                <div class="dropdown border-start ps-4 ms-2">
                    <div class="d-flex align-items-center gap-3" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-bold small">{{ $user->name ?? 'Người dùng' }}</div>
                            <div style="font-size:0.7rem; color: var(--primary)">{{ $user->position ?? 'Quản trị viên' }}</div>
                        </div>
                        <div class="avatar-box shadow-sm text-uppercase">{{ substr($user->name ?? 'AD', 0, 2) }}</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 animate__animated animate__fadeIn">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i class="fa-solid fa-user me-2"></i> Hồ sơ cá nhân</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 animate__animated animate__backInRight" role="alert" style="background-color: #10b981; color: white;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 animate__animated animate__shakeX" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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

        window.addEventListener('beforeunload', () => { document.getElementById('page-loader').style.display = 'block'; });
        window.addEventListener('pageshow', () => { document.getElementById('page-loader').style.display = 'none'; });
    </script>
</body>
</html>