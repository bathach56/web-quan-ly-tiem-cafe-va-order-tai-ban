<?php require_once '../app/views/inc/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* CSS Nâng cấp giao diện chuẩn SaaS */
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; }
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; }
    .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white; }
    .bg-gradient-welcome { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    
    .card-stat { border: none; border-radius: 15px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); transition: transform 0.3s; overflow: hidden; }
    .card-stat:hover { transform: translateY(-5px); }
    .card-stat .icon-bg { position: absolute; right: 10px; top: 15px; font-size: 4rem; opacity: 0.15; z-index: 0; }
    .card-stat .card-body { position: relative; z-index: 1; padding: 1.5rem; }
    
    .chart-container { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1); border: 1px solid #e3e6f0; }
    
    .table-modern { border-collapse: separate; border-spacing: 0 8px; }
    .table-modern tbody tr { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.2s; }
    .table-modern tbody tr:hover { transform: scale(1.01); box-shadow: 0 5px 15px rgba(141,85,36,0.15); }
    .table-modern td, .table-modern th { border: none; padding: 12px 15px; vertical-align: middle; }
    .table-modern tbody td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .table-modern tbody td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

    /* CSS cho Dòng thời gian (Timeline) */
    .timeline { position: relative; padding-left: 30px; list-style: none; margin-bottom: 0; }
    .timeline::before { content: ''; position: absolute; top: 0; bottom: 0; left: 9px; width: 2px; background: #e3e6f0; }
    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-item::before { content: ''; position: absolute; left: -27px; top: 5px; width: 14px; height: 14px; border-radius: 50%; background: #4e73df; border: 3px solid #fff; box-shadow: 0 0 0 2px #4e73df; z-index: 1; }
    .timeline-item.success::before { background: #1cc88a; box-shadow: 0 0 0 2px #1cc88a; }
    .timeline-item.warning::before { background: #f6c23e; box-shadow: 0 0 0 2px #f6c23e; }
    .timeline-item.danger::before { background: #e74c3c; box-shadow: 0 0 0 2px #e74c3c; }
</style>

<div class="container-fluid py-4 px-lg-4">

    <div class="row mb-4" data-aos="fade-down">
        <div class="col-12">
            <div class="card border-0 bg-gradient-welcome text-white rounded-4 shadow-sm">
                <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-2">Chào buổi sáng, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?>! 👋</h2>
                        <p class="mb-0 opacity-75 fs-6">Chúc bạn một ngày kinh doanh hồng phát. Dưới đây là tổng quan tình hình hệ thống.</p>
                    </div>
                    <div class="d-none d-md-text-end d-md-block text-end bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                        <h3 class="fw-bold mb-0 text-warning" id="liveClock">00:00:00</h3>
                        <small class="opacity-75 fs-6"><?= date('d/m/Y') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card card-stat bg-gradient-primary h-100">
                <div class="card-body">
                    <div class="text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Doanh thu (Hôm nay)</div>
                    <div class="h3 fw-bold mb-0"><?= isset($data['revenue_today']) ? number_format($data['revenue_today'], 0, ',', '.') : '1.450.000' ?> đ</div>
                    <div class="mt-2 text-white-50 small"><i class="fas fa-arrow-up text-white me-1"></i> +5.4% so với hôm qua</div>
                    <i class="fas fa-wallet icon-bg text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card card-stat bg-gradient-success h-100">
                <div class="card-body">
                    <div class="text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Đơn hàng hoàn tất</div>
                    <div class="h3 fw-bold mb-0"><?= isset($data['total_orders_today']) ? $data['total_orders_today'] : '24' ?></div>
                    <div class="mt-2 text-white-50 small"><i class="fas fa-check-circle text-white me-1"></i> Giao dịch thành công</div>
                    <i class="fas fa-receipt icon-bg text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card card-stat bg-gradient-warning h-100">
                <div class="card-body">
                    <div class="text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Bàn đang phục vụ</div>
                    <div class="h3 fw-bold mb-0"><?= isset($data['active_tables']) ? $data['active_tables'] : '5' ?></div>
                    <div class="mt-2 text-white-50 small"><i class="fas fa-clock text-white me-1"></i> Đang có khách tại quán</div>
                    <i class="fas fa-chair icon-bg text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="card card-stat bg-gradient-info h-100">
                <div class="card-body">
                    <div class="text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Tồn kho cảnh báo</div>
                    <div class="h3 fw-bold mb-0">3</div>
                    <div class="mt-2 text-white-50 small"><i class="fas fa-exclamation-triangle text-white me-1"></i> Cần nhập thêm nguyên liệu</div>
                    <i class="fas fa-box-open icon-bg text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7" data-aos="fade-right" data-aos-delay="100">
            <div class="chart-container h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-chart-area text-primary me-2"></i> Tổng quan doanh thu 7 ngày qua</h5>
                    <a href="<?= URLROOT ?>/report" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5" data-aos="fade-left" data-aos-delay="200">
            <div class="chart-container h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-chart-pie text-success me-2"></i> Tỷ trọng nhóm món</h5>
                <div style="height: 250px; display: flex; justify-content: center;">
                    <canvas id="topProductsChart"></canvas>
                </div>
                <div class="mt-4 text-center small fw-bold text-muted">
                    <span class="me-3"><i class="fas fa-circle text-primary me-1"></i> Cà phê</span>
                    <span class="me-3"><i class="fas fa-circle text-success me-1"></i> Trà trái cây</span>
                    <span><i class="fas fa-circle text-warning me-1"></i> Bánh ngọt</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" data-aos="fade-up" data-aos-delay="300">
        <div class="col-xl-8">
            <div class="chart-container h-100 p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-file-invoice-dollar text-secondary me-2"></i> Giao dịch mới nhất</h5>
                <div class="table-responsive">
                    <table class="table table-modern w-100 mb-0">
                        <thead class="text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                            <tr>
                                <th class="ps-3">Mã Đơn</th>
                                <th>Thời gian</th>
                                <th>Bàn</th>
                                <th>Thu ngân</th>
                                <th class="text-end">Tổng tiền</th>
                                <th class="text-center pe-3">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3 fw-bold text-primary">#ORD-0125</td>
                                <td>Vừa xong</td>
                                <td class="fw-bold">Bàn 1</td>
                                <td>Nhân viên 01</td>
                                <td class="text-end fw-bold">145.000 đ</td>
                                <td class="text-center pe-3"><span class="badge bg-success px-3 py-2 rounded-pill w-100">Hoàn tất</span></td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold text-primary">#ORD-0124</td>
                                <td>10 phút trước</td>
                                <td class="fw-bold">Bàn 3</td>
                                <td>Admin</td>
                                <td class="text-end fw-bold">85.000 đ</td>
                                <td class="text-center pe-3"><span class="badge bg-warning text-dark px-3 py-2 rounded-pill w-100">Đang phục vụ</span></td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold text-primary">#ORD-0123</td>
                                <td>25 phút trước</td>
                                <td class="fw-bold">Bàn 5</td>
                                <td>Nhân viên 02</td>
                                <td class="text-end fw-bold">210.000 đ</td>
                                <td class="text-center pe-3"><span class="badge bg-success px-3 py-2 rounded-pill w-100">Hoàn tất</span></td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold text-primary">#ORD-0122</td>
                                <td>1 giờ trước</td>
                                <td class="fw-bold">Bàn 2</td>
                                <td>Nhân viên 01</td>
                                <td class="text-end fw-bold">55.000 đ</td>
                                <td class="text-center pe-3"><span class="badge bg-danger px-3 py-2 rounded-pill w-100">Đã hủy</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="chart-container h-100 p-4">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-history text-secondary me-2"></i> Hoạt động hệ thống</h5>
                <ul class="timeline">
                    <li class="timeline-item success">
                        <span class="fw-bold text-dark d-block">Admin đã đăng nhập</span>
                        <small class="text-muted">Lúc 10:25 AM, IP: 192.168.1.1</small>
                    </li>
                    <li class="timeline-item">
                        <span class="fw-bold text-dark d-block">Thêm món mới: Trà Đào Cam Sả</span>
                        <small class="text-muted">Lúc 09:15 AM - Bởi Admin</small>
                    </li>
                    <li class="timeline-item warning">
                        <span class="fw-bold text-dark d-block">Cảnh báo: Hết Cà phê hạt</span>
                        <small class="text-muted">Hệ thống tự động báo cáo lúc 08:30 AM</small>
                    </li>
                    <li class="timeline-item success">
                        <span class="fw-bold text-dark d-block">Nhập kho: 10kg Cà phê hạt</span>
                        <small class="text-muted">Hôm qua - Bởi Admin</small>
                    </li>
                </ul>
                <div class="text-center mt-4">
                    <a href="#" class="text-primary fw-bold text-decoration-none">Xem toàn bộ lịch sử <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // ĐỒNG HỒ THỜI GIAN THỰC
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', { hour12: false });
        document.getElementById('liveClock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 1. Cấu hình Biểu đồ đường (Doanh thu)
    const ctxLine = document.getElementById('revenueChart').getContext('2d');
    let gradientLine = ctxLine.createLinearGradient(0, 0, 0, 300);
    gradientLine.addColorStop(0, 'rgba(78, 115, 223, 0.5)');
    gradientLine.addColorStop(1, 'rgba(78, 115, 223, 0.0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [1500000, 2100000, 1800000, 2400000, 3200000, 4500000, 1200000], 
                borderColor: '#4e73df',
                backgroundColor: gradientLine,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#4e73df',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#f3f3f3' }, beginAtZero: true }
            }
        }
    });

    // 2. Cấu hình Biểu đồ tròn (Món bán chạy)
    const ctxDoughnut = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Cà phê', 'Trà trái cây', 'Bánh ngọt'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#dda20a'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
});
</script>

<?php require_once '../app/views/inc/footer.php'; ?>