@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng chuyên nghiệp cho trang Kho hàng */
    .ingredient-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .ingredient-row:hover {
        background: rgba(217, 119, 6, 0.06) !important;
        transform: translateX(8px);
        box-shadow: 0 6px 16px -6px rgba(217, 119, 6, 0.25);
    }

    .stock-number {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.15rem;
    }

    .low-stock {
        color: #ef4444;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 dynamic-text">
                <i class="fa-solid fa-warehouse me-2"></i> Quản lý Kho Hàng
            </h3>
            <p class="text-secondary small m-0 mt-1">
                Theo dõi tồn kho nguyên liệu và thực hiện nhập/xuất kho.
            </p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fa-solid fa-plus me-1"></i> Khai báo nguyên liệu mới
        </button>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 110px;">MÃ NL</th>
                        <th>TÊN NGUYÊN LIỆU</th>
                        <th>ĐƠN VỊ</th>
                        <th class="text-center">TỒN KHO HIỆN TẠI</th>
                        <th class="text-center">MỨC CẢNH BÁO</th>
                        <th class="text-center">TRẠNG THÁI</th>
                        <th class="pe-4 text-center">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $item)
                    <tr class="ingredient-row">
                        <td class="ps-4 fw-bold text-primary">{{ $item->code }}</td>
                        <td class="fw-medium">{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $item->unit }}</span>
                        </td>
                        <td class="text-center">
                            <span class="stock-number {{ $item->stock <= $item->min_stock ? 'low-stock' : '' }}">
                                {{ number_format($item->stock) }}
                            </span>
                        </td>
                        <td class="text-center text-muted">{{ number_format($item->min_stock) }}</td>
                        <td class="text-center">
                            @if($item->stock <= $item->min_stock)
                                <span class="status-badge border-danger text-danger bg-danger bg-opacity-10">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Sắp hết hàng
                                </span>
                            @else
                                <span class="status-badge border-success text-success bg-success bg-opacity-10">
                                    <i class="fa-solid fa-check-circle me-1"></i> An toàn
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            <button class="btn btn-sm btn-success action-btn me-1 px-3"
                                    onclick="openStockModal('import', {{ $item->id }}, '{{ addslashes($item->name) }}')">
                                <i class="fa-solid fa-arrow-down"></i> Nhập
                            </button>
                            <button class="btn btn-sm btn-warning action-btn px-3"
                                    onclick="openStockModal('export', {{ $item->id }}, '{{ addslashes($item->name) }}')">
                                <i class="fa-solid fa-arrow-up"></i> Xuất
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-box-open fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Chưa có nguyên liệu nào trong kho.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM NGUYÊN LIỆU MỚI ==================== -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('inventory.store') }}" method="POST" class="modal-content" style="background: var(--card-bg); color: var(--text-gray);">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Khai báo Nguyên liệu mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Mã nguyên liệu <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control adaptive-input" placeholder="VD: NL-001" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Tên nguyên liệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control adaptive-input" placeholder="VD: Cà phê hạt Arabica" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Đơn vị tính <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control adaptive-input" placeholder="Kg, Lít, Hộp..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Mức tồn kho tối thiểu</label>
                        <input type="number" name="min_stock" class="form-control adaptive-input" value="5" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4 fw-bold">Lưu nguyên liệu</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL NHẬP/XUẤT KHO ==================== -->
<div class="modal fade" id="modalStock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('inventory.update_stock') }}" method="POST" class="modal-content" style="background: var(--card-bg); color: var(--text-gray);">
            @csrf
            <input type="hidden" name="id" id="stock_item_id">
            <input type="hidden" name="type" id="stock_type">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="stockModalTitle">Nhập/Xuất Kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <p class="text-secondary small mb-2">Nguyên liệu:</p>
                <h5 class="fw-bold text-primary mb-4" id="stockItemName"></h5>
                
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control form-control-lg adaptive-input text-center fw-bold" 
                           placeholder="Nhập số lượng" required min="0.1" step="0.1">
                </div>
                
                <div>
                    <label class="small fw-bold mb-1">Ghi chú</label>
                    <textarea name="note" class="form-control adaptive-input" rows="2" 
                              placeholder="Ví dụ: Nhập lô hàng tháng 4..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" id="btnStockSubmit" class="btn btn-success px-4 fw-bold">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStockModal(type, id, name) {
    document.getElementById('stock_item_id').value = id;
    document.getElementById('stock_type').value = type;
    document.getElementById('stockItemName').textContent = name;

    const title = document.getElementById('stockModalTitle');
    const btn = document.getElementById('btnStockSubmit');

    if (type === 'import') {
        title.innerHTML = `<i class="fa-solid fa-circle-down text-success me-2"></i> NHẬP KHO`;
        btn.className = 'btn btn-success px-4 fw-bold';
        btn.textContent = 'Xác nhận nhập kho';
    } else {
        title.innerHTML = `<i class="fa-solid fa-circle-up text-danger me-2"></i> XUẤT KHO`;
        btn.className = 'btn btn-danger px-4 fw-bold';
        btn.textContent = 'Xác nhận xuất kho';
    }

    const modal = new bootstrap.Modal(document.getElementById('modalStock'));
    modal.show();
}
</script>

@endsection