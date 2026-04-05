<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? SITENAME ?></title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #8d5524;
        }
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .cart-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar đơn giản cho khách hàng -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-coffee me-2"></i> <?= SITENAME ?>
            </a>
            <div class="d-flex align-items-center">
                <span class="badge bg-success fs-6 me-3">
                    Bàn <strong><?= $table['table_name'] ?? '??' ?></strong>
                </span>
                <a href="<?= URLROOT ?>/order/pos" class="btn btn-outline-light btn-sm" target="_blank">
                    <i class="fas fa-cash-register"></i> POS Nhân viên
                </a>
            </div>
        </div>
    </nav>