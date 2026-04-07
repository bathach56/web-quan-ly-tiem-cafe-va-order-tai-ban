<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Hệ thống Quản lý Coffee Shop' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* CSS Layout Chung */
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar Admin/Staff */
        #sidebar { min-width: 260px; max-width: 260px; background: #343a40; color: #fff; min-height: 100vh; transition: all 0.3s; z-index: 1000; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        #sidebar .sidebar-header { padding: 20px; background: #23272b; text-align: center; border-bottom: 1px solid #4b545c; }
        #sidebar .sidebar-header h4 { margin: 0; font-weight: bold; color: #ffc107; letter-spacing: 1px; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 25px; font-size: 1.05em; display: block; color: #c2c7d0; text-decoration: none; border-left: 4px solid transparent; transition: 0.2s; }
        #sidebar ul li a:hover { color: #fff; background: #495057; border-left-color: #ffc107; }
        #sidebar ul li a i { margin-right: 12px; width: 20px; text-align: center; }
        
        /* Main Content & Topbar */
        #content { width: 100%; min-height: 100vh; transition: all 0.3s; display: flex; flex-direction: column; }
        .top-navbar { background: #fff; padding: 15px 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .user-info { font-weight: 600; color: #495057; }
        
        @media (max-width: 768px) {
            #sidebar { margin-left: -260px; position: fixed; height: 100vh; overflow-y: auto; }
            #sidebar.active { margin-left: 0; }
        }

        /* Animation Hover & Giao diện phụ */
        .btn { transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card { transition: all 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background-color: rgba(141, 85, 36, 0.05) !important; }
    </style>
</head>
<body>

<div id="wrapper">
    <?php if(isset($_SESSION['user_id'])): ?>
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-coffee me-2"></i>COFFEE SHOP</h4>
            <small class="text-light opacity-75">Quản trị & Bán hàng</small>
        </div>

        <ul class="list-unstyled components">
            <li class="nav-item">
                <a href="<?= URLROOT ?>/dashboard"><i class="fas fa-tachometer-alt"></i> Tổng Quan</a>
            </li>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
            <li class="nav-item">
                <a href="<?= URLROOT ?>/order/pos"><i class="fas fa-desktop"></i> Thu Ngân (POS)</a>
            </li>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/product"><i class="fas fa-box-open"></i> Thực Đơn (Menu)</a>
                </li>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/inventory"><i class="fas fa-boxes"></i> Kho Hàng</a>
                </li>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/staff"><i class="fas fa-users"></i> Nhân Sự</a>
                </li>
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/report"><i class="fas fa-chart-line"></i> Báo Cáo Doanh Thu</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <div id="content">
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary d-md-none me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 text-muted fw-bold d-none d-md-block">Hệ Thống Quản Lý</h5>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="user-info me-3 d-none d-sm-block">
                    <i class="fas fa-user-circle fs-5 me-1 text-primary align-middle"></i> 
                    <?= htmlspecialchars($_SESSION['full_name'] ?? 'Khách') ?> 
                    <small class="badge bg-<?= ($_SESSION['role'] ?? '') === 'admin' ? 'danger' : 'info text-dark' ?> ms-1">
                        <?= ($_SESSION['role'] ?? '') === 'admin' ? 'Admin' : 'Staff' ?>
                    </small>
                </div>
                <a href="<?= URLROOT ?>/auth/logout" class="btn btn-danger btn-sm fw-bold shadow-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-dark text-white p-3 text-center shadow-sm mb-4">
            <h4 class="mb-0 text-warning fw-bold"><i class="fas fa-coffee me-2"></i>COFFEE SHOP</h4>
        </div>
        <?php endif; ?>

        <div class="container-fluid px-md-4 pb-5">