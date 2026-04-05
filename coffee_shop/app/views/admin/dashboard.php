<?php require_once '../app/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">
            <i class="fas fa-tachometer-alt text-warning"></i> Dashboard Admin
        </h1>
        <div>
            <span class="badge bg-success fs-6">Xin chào, <strong><?= htmlspecialchars($full_name) ?></strong></span>
            <a href="<?= URLROOT ?>/auth/logout" class="btn btn-outline-danger btn-sm ms-3">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </div>

    <!-- Thống kê Cards -->
    <div class="row g-4">
        <!-- Card 1: Sản phẩm -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Tổng Sản Phẩm</h6>
                            <h2 class="fw-bold text-primary"><?= number_format($total_products) ?></h2>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-coffee fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Đơn hàng -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Tổng Đơn Hàng</h6>
                            <h2 class="fw-bold text-success"><?= number_format($total_orders) ?></h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-receipt fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Bàn -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Tổng Số Bàn</h6>
                            <h2 class="fw-bold"><?= number_format($total_tables) ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chair fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Doanh thu hôm nay -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="opacity-75">Doanh Thu Hôm Nay</h6>
                            <h2 class="fw-bold"><?= number_format($today_revenue) ?> đ</h2>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần thứ 2: Trạng thái bàn -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Trạng thái bàn hiện tại</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-4 border rounded text-center">
                                <h3 class="text-success"><?= $total_tables - $occupied_tables ?></h3>
                                <p class="text-muted mb-0">Bàn trống</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 border rounded text-center">
                                <h3 class="text-warning"><?= $occupied_tables ?></h3>
                                <p class="text-muted mb-0">Bàn đang có khách</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 border rounded text-center">
                                <h3 class="text-primary"><?= $total_tables ?></h3>
                                <p class="text-muted mb-0">Tổng bàn</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="mb-3">Chức năng nhanh</h5>
            <div class="row g-4">
                <div class="col-md-3">
                    <a href="<?= URLROOT ?>/product" class="text-decoration-none">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-coffee fa-3x text-warning mb-3"></i>
                                <h5>Quản lý Món Ăn</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= URLROOT ?>/order/pos" class="text-decoration-none">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-cash-register fa-3x text-success mb-3"></i>
                                <h5>Mở POS Thu Ngân</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                <h5>Báo cáo Doanh thu</h5>
                                <small class="text-muted">(Đang phát triển)</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once '../app/views/inc/footer.php'; ?>