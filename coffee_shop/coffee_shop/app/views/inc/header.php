<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Hệ thống Quản lý Coffee Shop' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .navbar-brand { 
            font-weight: 700; 
            color: #f8c146 !important; 
            letter-spacing: 1px;
        }
        .nav-link { font-weight: 500; }
        .nav-link.active { color: #f8c146 !important; }
    </style>

    <script>
        // Bắn tín hiệu "Ping" về server mỗi 5 giây để báo Tab này vẫn đang mở
        setInterval(function() {
            fetch('<?= URLROOT ?>/auth/ping')
                .then(response => response.json())
                .catch(error => console.log('Mất kết nối máy chủ'));
        }, 5000); 
    </script>
</head>
<body>

    <?php if(isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= URLROOT ?>/dashboard">
                <i class="fas fa-mug-hot me-2"></i>COFFEE SHOP
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="topNavBar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/dashboard"><i class="fas fa-chart-pie me-1"></i> Tổng quan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URLROOT ?>/product"><i class="fas fa-box-open me-1"></i> Quản lý món ăn</a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?= URLROOT ?>/order/pos"><i class="fas fa-desktop me-1"></i> Màn hình Thu ngân (POS)</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <div class="text-light me-3">
                        <i class="fas fa-user-circle fs-5 align-middle me-1"></i>
                        <span class="align-middle fw-medium"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Nhân viên') ?></span>
                        <span class="badge bg-secondary ms-1"><?= strtoupper($_SESSION['role'] ?? '') ?></span>
                    </div>
                    <a href="<?= URLROOT ?>/auth/logout" class="btn btn-outline-danger btn-sm fw-bold">
                        <i class="fas fa-sign-out-alt"></i> Thoát
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container-fluid">