@extends('layouts.admin')

@section('content')
<style>
    /* CSS Tùy chỉnh riêng cho trang Báo cáo */
    .report-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Thẻ Tổng doanh thu (Màu xanh lá) */
    .summary-card-revenue {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }

    /* Thẻ Tổng đơn hàng (Màu xanh dương/cyan) */
    .summary-card-orders {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.3);
    }

    /* Style cho ô chọn ngày */
    .date-input {
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border-color);
        color: var(--text-gray);
    }
    .date-input:focus {
        background: var(--card-bg);
        color: var(--text-gray);
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(217, 119, 6, 0.25);
    }
    
    /* Bảng báo cáo */
    .report-table {
        background: transparent !important;
        color: var(--text-gray) !important;
    }
    .report-table th {
        background: rgba(0, 0, 0, 0.1) !important;
        border-bottom: 2px solid var(--border-color) !important;
        color: var(--primary);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 15px;
    }
    .report-table td {
        border-bottom: 1px solid var(--border-color) !important;
        background: transparent !important;
        padding: 16px 12px;
        vertical-align: middle;
    }

    .dynamic-text { transition: color 0.3s ease; }
    [data-theme="dark"] .dynamic-text { color: #ffffff; }
    [data-theme="light"] .dynamic-text { color: #111827; }

    .product-rank-item {
        border-bottom: 1px solid var(--border-color);
        padding: 10px 0;
    }
    .product-rank-item:last-child { border: none; }
</style>

<div class="container-fluid pb-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold m-0 dynamic-text"><i class="fa-solid fa-chart-line text-primary me-2"></i> Báo Cáo Doanh Thu</h3>
            <p class="text-secondary small m-0 mt-1">Hệ thống quản lý {{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="btn btn-danger btn-sm px-3 fw-bold shadow-sm" style="background: #ef4444; border: none;">
                <i class="fa-solid fa-file-pdf me-1"></i> Xuất PDF
            </a>
            
            <a href="{{ route('reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="btn btn-success btn-sm px-3 fw-bold shadow-sm" style="background: #10b981; border: none;">
                <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="report-card p-4 mb-4">
        <form action="{{ route('reports.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold mb-2 dynamic-text text-uppercase">Từ ngày:</label>
                    <input type="date" name="start_date" class="form-control date-input" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-2 dynamic-text text-uppercase">Đến ngày:</label>
                    <input type="date" name="end_date" class="form-control date-input" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm text-uppercase">
                        <i class="fa-solid fa-filter me-2"></i> Cập nhật báo cáo
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="summary-card-revenue animate__animated animate__fadeInLeft">
                <h6 class="fw-bold mb-2" style="letter-spacing: 1px; opacity: 0.9;">TỔNG DOANH THU KỲ BÁO CÁO</h6>
                <h1 class="fw-bold m-0" style="font-size: 2.5rem;">{{ number_format($totalRevenue) }} <span style="font-size: 1.5rem;">VNĐ</span></h1>
            </div>
        </div>
        <div class="col-md-6">
            <div class="summary-card-orders animate__animated animate__fadeInRight">
                <h6 class="fw-bold mb-2" style="letter-spacing: 1px; opacity: 0.9;">TỔNG SỐ ĐƠN HÀNG</h6>
                <h1 class="fw-bold m-0" style="font-size: 2.5rem;">{{ number_format($totalOrders) }} <span style="font-size: 1.5rem;">Đơn</span></h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="report-card overflow-hidden p-0 animate__animated animate__fadeInUp">
                <div class="p-3 border-bottom border-color fw-bold dynamic-text bg-light bg-opacity-10">
                    <i class="fa-solid fa-list me-2"></i> CHI TIẾT DOANH THU THEO NGÀY
                </div>
                <div class="table-responsive">
                    <table class="table report-table mb-0 text-center">
                        <thead>
                            <tr>
                                <th class="ps-4 text-start fw-bold">Ngày giao dịch</th>
                                <th class="fw-bold">Số lượng đơn</th>
                                <th class="pe-4 text-end fw-bold">Doanh thu ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td class="ps-4 text-start fw-bold text-primary">{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                                <td class="dynamic-text fw-bold fs-5">{{ number_format($report->total_orders) }}</td>
                                <td class="pe-4 text-end fw-bold text-success">{{ number_format($report->daily_revenue) }} đ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-box-open fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Không có dữ liệu trong khoảng thời gian này.</p>
                                </td>
                            </tr>
                            @endforelse
                            
                            @if($reports->count() > 0)
                            <tr style="background: rgba(0,0,0,0.05);">
                                <td class="ps-4 text-start fw-bold dynamic-text fs-5">TỔNG CỘNG</td>
                                <td class="fw-bold dynamic-text fs-4 text-info">{{ number_format($totalOrders) }}</td>
                                <td class="pe-4 text-end fw-bold fs-4" style="color: #10b981;">{{ number_format($totalRevenue) }} đ</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="report-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="p-3 border-bottom border-color fw-bold text-warning bg-light bg-opacity-10">
                    <i class="fa-solid fa-crown me-2"></i> TOP MÓN BÁN CHẠY
                </div>
                <div class="card-body">
                    @forelse($topProducts ?? [] as $index => $product)
                    <div class="product-rank-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $index == 0 ? 'bg-warning text-dark' : ($index == 1 ? 'bg-light text-dark border' : 'bg-secondary') }} me-3" style="width: 25px;">{{ $index + 1 }}</span>
                            <span class="dynamic-text fw-bold small text-truncate" style="max-width: 150px;">{{ $product->name }}</span>
                        </div>
                        <span class="text-info fw-bold">{{ $product->total_qty }} <small>ly</small></span>
                    </div>
                    @empty
                    <p class="text-center text-muted py-4 small">Chưa có dữ liệu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection