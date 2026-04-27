@extends('layouts.admin')

@section('content')
<style>
    /* ============================================================
       DASHBOARD GLOW SYSTEM (Nhóm 3 - 23DTHB6)
       ============================================================ */
    :root {
        --glow-amber: rgba(217, 119, 6, 0.4);
        --glow-green: rgba(16, 185, 129, 0.4);
        --glow-sky:   rgba(14, 165, 233, 0.4);
        --glow-rose:  rgba(239, 68, 68, 0.4);
    }

    @keyframes shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
    @keyframes floatUp { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
    @keyframes pulse-glow { 0% { box-shadow: 0 0 5px var(--ring-color); } 50% { box-shadow: 0 0 20px var(--ring-color); } 100% { box-shadow: 0 0 5px var(--ring-color); } }

    /* --- WELCOME BANNER --- */
    .welcome-banner {
        position: relative;
        background: linear-gradient(135deg, #d97706 0%, #92400e 100%);
        border-radius: 24px;
        color: #fff;
        padding: 35px;
        box-shadow: 0 10px 30px -5px var(--glow-amber);
        overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(110deg, transparent 25%, rgba(255,255,255,0.1) 50%, transparent 75%);
        background-size: 800px 100%;
        animation: shimmer 4s linear infinite;
    }

    .clock-box {
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 15px 30px;
        text-align: center;
        animation: floatUp 4s ease-in-out infinite;
    }
    .clock-time { font-family: 'Inter', monospace; font-size: 2.5rem; font-weight: 800; color: #fff; line-height: 1; }

    /* --- STAT CARDS --- */
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 25px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-10px); }
    .stat-card.amber:hover { border-color: #f59e0b; box-shadow: 0 15px 30px -10px var(--glow-amber); }
    .stat-card.green:hover { border-color: #10b981; box-shadow: 0 15px 30px -10px var(--glow-green); }
    .stat-card.sky:hover   { border-color: #0ea5e9; box-shadow: 0 15px 30px -10px var(--glow-sky); }
    .stat-card.rose:hover  { border-color: #ef4444; box-shadow: 0 15px 30px -10px var(--glow-rose); }

    .stat-icon {
        width: 54px; height: 54px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
        --ring-color: rgba(255,255,255,0.1);
        animation: pulse-glow 3s infinite;
    }
    .stat-icon.amber { background: rgba(217,119,6,0.1); color: #f59e0b; --ring-color: var(--glow-amber); }
    .stat-icon.green { background: rgba(16,185,129,0.1); color: #10b981; --ring-color: var(--glow-green); }
    .stat-icon.sky   { background: rgba(14,165,233,0.1); color: #0ea5e9; --ring-color: var(--glow-sky); }
    .stat-icon.rose  { background: rgba(239,68,68,0.1); color: #ef4444; --ring-color: var(--glow-rose); }

    .stat-value { font-size: 1.8rem; font-weight: 800; margin-bottom: 5px; color: var(--text-gray); }
    .stat-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-gray); opacity: 0.6; }

    /* --- CHARTS --- */
    .chart-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 25px;
        height: 100%;
    }
    .chart-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; }
    .chart-title { font-weight: 700; font-size: 1rem; color: var(--text-gray); }

    [data-theme="light"] .clock-box { background: rgba(255,255,255,0.2); }
</style>

<div class="container-fluid pb-5">
    {{-- WELCOME BANNER --}}
    <div class="welcome-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-4">
        <div>
            <h2 class="fw-800 mb-2 animate__animated animate__fadeInLeft" id="greeting">Đang tải...</h2>
            <p class="mb-0 opacity-90 fs-5 animate__animated animate__fadeInLeft animate__delay-1s">
                Hôm nay bạn muốn kiểm tra số liệu nào cho <b>HUTECH Coffee</b>?
            </p>
        </div>
        <div class="clock-box animate__animated animate__fadeInRight">
            <div class="clock-time" id="clock">00:00</div>
            <div class="clock-date mt-2 opacity-75 small" id="clockDate">Ngày tháng năm</div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card amber">
                <div class="stat-icon amber"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="stat-value">{{ number_format($todayRevenue) }} ₫</div>
                <div class="stat-label">Doanh thu hôm nay</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <div class="stat-icon green"><i class="fa-solid fa-mug-saucer"></i></div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Sản phẩm đang bán</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card sky">
                <div class="stat-icon sky"><i class="fa-solid fa-couch"></i></div>
                <div class="stat-value">{{ $activeTables }}</div>
                <div class="stat-label">Bàn đang có khách</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card rose">
                <div class="stat-icon rose"><i class="fa-solid fa-tags"></i></div>
                <div class="stat-value">{{ $totalCategories }}</div>
                <div class="stat-label">Danh mục Menu</div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title"><i class="fa-solid fa-chart-line text-warning me-2"></i>Biểu đồ doanh thu (7 ngày)</div>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Đơn vị: VNĐ</span>
                </div>
                <div style="height: 350px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title"><i class="fa-solid fa-chart-pie text-success me-2"></i>Tỉ lệ bán theo nhóm</div>
                </div>
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /* 1. XỬ LÝ LỜI CHÀO & ĐỒNG HỒ */
    function updateHeader() {
        const now = new Date();
        const hour = now.getHours();
        const name = "{{ auth()->user()->name ?? 'Thành viên Nhóm 3' }}";
        let greet = "Chào buổi tối";
        
        if (hour < 12) greet = "Chào buổi sáng";
        else if (hour < 18) greet = "Chào buổi chiều";
        
        document.getElementById('greeting').textContent = `${greet}, ${name}! 👋`;
        document.getElementById('clock').textContent = now.toLocaleTimeString('vi-VN', { hour12: false });
        document.getElementById('clockDate').textContent = now.toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }
    setInterval(updateHeader, 1000);
    updateHeader();

    /* 2. CẤU HÌNH BIỂU ĐỒ */
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = () => document.body.getAttribute('data-theme') !== 'light';
        const getLabelColor = () => isDark() ? '#94a3b8' : '#64748b';
        const getGridColor = () => isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

        // Biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChartData = @json($chartData);
        
        const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
        revGradient.addColorStop(0, 'rgba(217, 119, 6, 0.3)');
        revGradient.addColorStop(1, 'rgba(217, 119, 6, 0)');

        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueChartData.map(d => {
                    const date = new Date(d.date);
                    return `${date.getDate()}/${date.getMonth() + 1}`;
                }),
                datasets: [{
                    label: 'Doanh thu',
                    data: revenueChartData.map(d => d.total),
                    borderColor: '#d97706',
                    borderWidth: 4,
                    backgroundColor: revGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#d97706',
                    pointBorderColor: '#fff',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 15,
                        backgroundColor: isDark() ? '#1c1917' : '#fff',
                        titleColor: isDark() ? '#fff' : '#1c1917',
                        bodyColor: '#d97706',
                        borderColor: '#d97706',
                        borderWidth: 1,
                        callbacks: { label: (ctx) => ` ${ctx.raw.toLocaleString()} ₫` }
                    }
                },
                scales: {
                    y: { grid: { color: getGridColor() }, ticks: { color: getLabelColor() } },
                    x: { grid: { display: false }, ticks: { color: getLabelColor() } }
                }
            }
        });

        // Biểu đồ hình tròn
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const pieData = @json($pieChartData);
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: pieData.map(d => d.name),
                datasets: [{
                    data: pieData.map(d => d.total_qty),
                    backgroundColor: ['#d97706', '#10b981', '#0ea5e9', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: getLabelColor(), padding: 20, usePointStyle: true }
                    }
                }
            }
        });
    });
</script>
@endsection