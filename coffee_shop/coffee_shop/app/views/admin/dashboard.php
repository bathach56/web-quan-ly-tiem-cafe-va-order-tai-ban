<?php require_once '../app/views/inc/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-chart-pie text-primary me-2"></i> Tổng Quan Hệ Thống</h2>
            <p class="text-muted mb-0">Xin chào, <strong><?= htmlspecialchars($data['full_name']) ?></strong>! Chúc bạn một ngày làm việc hiệu quả.</p>
        </div>
        <div>
            <a href="<?= URLROOT ?>/order/pos" class="btn btn-warning btn-lg fw-bold shadow-sm text-dark">
                <i class="fas fa-desktop me-2"></i>Mở Quầy Thu Ngân
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-2 opacity-75">Doanh thu hôm nay</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($data['today_revenue'], 0, ',', '.') ?> <small class="fs-6">đ</small></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #007bff 0%, #00d2ff 100%);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-2 opacity-75">Tổng số đơn hàng</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($data['total_orders']) ?> <small class="fs-6">Đơn</small></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="fas fa-receipt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-2 opacity-75">Thực đơn (Menu)</h6>
                        <h3 class="fw-bold mb-0"><?= number_format($data['total_products']) ?> <small class="fs-6">Món</small></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="fas fa-coffee"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold mb-2 opacity-75">Bàn đang phục vụ</h6>
                        <h3 class="fw-bold mb-0"><?= $data['occupied_tables'] ?> / <?= $data['total_tables'] ?> <small class="fs-6">Bàn</small></h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="fas fa-chair"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Biểu đồ Doanh thu tuần</h5>
                    <span class="badge bg-light text-dark border">Cập nhật lúc: <?= date('H:i') ?></span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-bolt text-warning me-2"></i>Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="<?= URLROOT ?>/product" class="btn btn-outline-primary text-start p-3 fw-semibold shadow-sm">
                            <i class="fas fa-box-open fa-fw me-2 fs-5 align-middle"></i> Quản lý Thực Đơn (Menu)
                        </a>
                        <a href="<?= URLROOT ?>/order/pos" class="btn btn-outline-success text-start p-3 fw-semibold shadow-sm">
                            <i class="fas fa-cash-register fa-fw me-2 fs-5 align-middle"></i> Mở quầy Thu Ngân (POS)
                        </a>
                        <button class="btn btn-outline-info text-start p-3 fw-semibold shadow-sm" onclick="alert('Tính năng Quản lý nhân viên đang được nâng cấp!')">
                            <i class="fas fa-users fa-fw me-2 fs-5 align-middle"></i> Quản lý Nhân Viên
                        </button>
                        <button class="btn btn-outline-secondary text-start p-3 fw-semibold shadow-sm" onclick="alert('Tính năng Báo cáo chi tiết đang được nâng cấp!')">
                            <i class="fas fa-file-invoice-dollar fa-fw me-2 fs-5 align-middle"></i> Xuất Báo Cáo Doanh Thu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Dữ liệu minh họa kết hợp với doanh thu thực tế hôm nay
    const todayRevenue = <?= $data['today_revenue'] ?>;
    
    // Khởi tạo biểu đồ
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Hôm nay'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                // Dữ liệu giả lập 6 ngày trước + Doanh thu thật của hôm nay
                data: [1250000, 1900000, 1400000, 2200000, 1800000, 3500000, todayRevenue > 0 ? todayRevenue : 0],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000) + ' Tr'; // Rút gọn trục Y thành đơn vị Triệu
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once '../app/views/inc/footer.php'; ?>