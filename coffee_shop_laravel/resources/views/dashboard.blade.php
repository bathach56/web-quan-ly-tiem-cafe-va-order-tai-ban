@extends('layouts.admin')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #d97706, #b45309);
        border-radius: 16px;
        color: white;
        padding: 28px 32px;
        box-shadow: 0 10px 20px -5px rgba(217, 119, 6, 0.4);
    }

    .welcome-text {
        font-size: 1.45rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .clock-box {
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 18px 24px;
        text-align: center;
        min-width: 180px;
    }

    .clock-time {
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 2.1rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: #fff;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .clock-date {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.85);
        margin-top: 4px;
    }

    .dashboard-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        transition: transform 0.2s;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
    }
</style>

<div class="container-fluid pb-4">
    
    <div class="welcome-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-4 animate__animated animate__fadeIn">
        <div>
            <h3 class="welcome-text mb-2" id="greeting">
                </h3>
            <p class="mb-0 opacity-90" style="font-size: 1.02rem;">
                Chúc bạn một ca làm việc hiệu quả. Dưới đây là tổng quan tình hình kinh doanh hôm nay.
            </p>
        </div>

        <div class="clock-box shadow-sm">
            <div class="clock-time" id="clock">00:00:00</div>
            <div class="clock-date" id="date">Thứ Sáu, 17 tháng 4, 2026</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3 animate__animated animate__fadeInUp">
            <div class="dashboard-card shadow-sm">
                <div class="small text-uppercase mb-1 fw-bold text-muted">Doanh thu hôm nay</div>
                <h3 class="fw-bold mb-1 text-warning">{{ number_format($todayRevenue) }} đ</h3>
                <div class="small text-success"><i class="fa-solid fa-arrow-up"></i> Cập nhật trực tiếp</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="dashboard-card shadow-sm">
                <div class="small text-uppercase mb-1 fw-bold text-muted">Món đang bán</div>
                <h3 class="fw-bold mb-1 text-success">{{ $totalProducts }}</h3>
                <div class="small text-success">Sẵn sàng phục vụ</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="dashboard-card shadow-sm">
                <div class="small text-uppercase mb-1 fw-bold text-muted">Bàn đang dùng</div>
                <h3 class="fw-bold mb-1 text-info">{{ $activeTables }}</h3>
                <div class="small text-warning">Khách đang ngồi</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <div class="dashboard-card shadow-sm">
                <div class="small text-uppercase mb-1 fw-bold text-muted">Danh mục Menu</div>
                <h3 class="fw-bold mb-1 text-danger">{{ $totalCategories }}</h3>
                <div class="small">Phân loại rõ ràng</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8 animate__animated animate__fadeInLeft">
            <div class="dashboard-card shadow-sm">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-line me-2 text-warning"></i>Doanh thu 7 ngày qua</h6>
                <div style="height: 320px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 animate__animated animate__fadeInRight">
            <div class="dashboard-card shadow-sm">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-2 text-success"></i>Nhóm món bán chạy</h6>
                <div style="height: 280px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== LOGIC CHÀO THEO BUỔI ====================
function setGreeting() {
    const hour = new Date().getHours();
    const name = "{{ auth()->user()->name ?? 'Quản Trị Viên' }}"; // Lấy tên thật từ Auth
    let greetingText = "";

    if (hour >= 5 && hour < 12) {
        greetingText = `Chào buổi sáng, ${name}! 🌅`;
    } else if (hour >= 12 && hour < 14) {
        greetingText = `Chào buổi trưa, ${name}! ☀️`;
    } else if (hour >= 14 && hour < 18) {
        greetingText = `Chào buổi chiều, ${name}! 🌤️`;
    } else {
        greetingText = `Chào buổi tối, ${name}! 🌙`;
    }

    document.getElementById('greeting').textContent = greetingText;
}

// ==================== ĐỒNG HỒ ====================
function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent = now.toLocaleTimeString('vi-VN', { hour12: false });
    document.getElementById('date').textContent = now.toLocaleDateString('vi-VN', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
}

setGreeting();
updateClock();
setInterval(updateClock, 1000);

// ==================== XỬ LÝ BIỂU ĐỒ (DỮ LIỆU THỰC) ====================
document.addEventListener('DOMContentLoaded', function() {
    // 1. Dữ liệu Revenue Chart (Line Chart)
    const chartData = @json($chartData);
    const lineLabels = chartData.map(item => {
        const d = new Date(item.date);
        return `${d.getDate()}/${d.getMonth() + 1}`;
    });
    const lineValues = chartData.map(item => item.total);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: lineValues,
                borderColor: '#fbbf24',
                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                fill: true,
                pointBackgroundColor: '#fbbf24'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Dữ liệu Category Chart (Pie Chart)
    const pieDataRaw = @json($pieChartData);
    const pieLabels = pieDataRaw.map(item => item.name);
    const pieValues = pieDataRaw.map(item => item.total_qty);

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: ['#d97706', '#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, color: '#94a3b8' } }
            },
            cutout: '70%'
        }
    });
});
</script>

@endsection