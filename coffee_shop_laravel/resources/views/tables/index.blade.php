@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng uốn lượn chuyên nghiệp cho hàng */
    .table-row {
        transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
    }
    
    .table-row:hover {
        background: rgba(217, 119, 6, 0.04) !important;
        transform: scale(1.002);
    }

    /* KHUNG TRẮNG BO CONG 30PX CHUẨN PREMIUM */
    .table-card {
        background-color: #ffffff !important;
        border: none !important;
        border-radius: 30px !important;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4) !important;
        overflow: hidden;
        padding: 10px;
        animation: fadeInUp 0.5s ease-out;
    }

    /* Tinh chỉnh Header bảng */
    .table thead th {
        background-color: #ffffff;
        color: #1c1917;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 22px 20px;
        border-bottom: 2px solid #f3f2f1;
    }

    /* Tinh chỉnh nội dung hàng */
    .table tbody td {
        padding: 18px 20px;
        color: #44403c;
        vertical-align: middle;
        border-bottom: 1px solid #f8f7f6;
        font-weight: 500;
    }

    /* Badge trạng thái bàn */
    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Thanh tìm kiếm Premium */
    .search-box {
        border-radius: 15px !important;
        border: 1.5px solid #e7e5e4 !important;
        padding-left: 45px !important;
        height: 45px;
        background-color: #f9f8f7;
        transition: 0.3s;
    }
    
    .search-box:focus {
        border-color: var(--primary) !important;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1) !important;
    }

    .search-icon-wrapper {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a8a29e;
        z-index: 5;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid pb-4">
    <!-- Header Section - Tiêu đề màu vàng hổ phách -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 text-warning">
                <i class="fa-solid fa-couch me-2"></i> QUẢN LÝ SƠ ĐỒ BÀN
            </h3>
            <p class="text-secondary small m-0 mt-1">Thiết lập vị trí bàn và tạo mã QR Order dành cho khách tại HUTECH Coffee.</p>
        </div>
        
        <div class="d-flex gap-2">
            <button id="btn-delete-selected" class="btn btn-danger px-3 rounded-pill fw-bold d-none shadow-sm" onclick="confirmBulkDelete()">
                <i class="fa-solid fa-trash me-1"></i> XÓA ĐÃ CHỌN (<span id="selected-count">0</span>)
            </button>

            <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fa-solid fa-plus me-1"></i> THÊM BÀN MỚI
            </button>
        </div>
    </div>

    <!-- Thanh Tìm Kiếm & Lọc -->
    <div class="card border-0 mb-4 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,0.05);">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="fa-solid fa-magnifying-glass search-icon-wrapper"></i>
                        <input type="text" id="tableSearchInput" class="form-control search-box" placeholder="Tìm tên bàn hoặc khu vực...">
                    </div>
                </div>
                <div class="col-md-7 text-md-end mt-2 mt-md-0">
                    <span class="text-secondary small fw-bold">Tổng số: <span id="visibleTableCount" class="text-warning">{{ count($tables) }}</span> bàn phục vụ</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng Dữ Liệu Bo Cong 30px (Khối trắng Premium) -->
    <div class="table-card">
        <form id="bulk-delete-form" action="{{ route('tables.bulk_destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="table-responsive">
                <table class="table mb-0" id="mainTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th>TÊN BÀN</th>
                            <th>KHU VỰC</th>
                            <th class="text-center">TRẠNG THÁI</th>
                            <th class="pe-4 text-end">THIẾT LẬP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tables as $table)
                        <tr class="table-row">
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $table->id }}" class="row-checkbox form-check-input">
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $table->name }}</div>
                                <small class="text-muted">ID: #{{ $table->id }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2" style="border-radius: 10px; font-weight: 600;">
                                    {{ $table->area ?? 'Chưa phân khu' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($table->status == 'available')
                                    <span class="status-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="fa-solid fa-circle-check me-1"></i> TRỐNG
                                    </span>
                                @elseif($table->status == 'pending')
                                    <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                        <i class="fa-solid fa-clock me-1"></i> CHỜ XÁC NHẬN
                                    </span>
                                @else
                                    <span class="status-badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <i class="fa-solid fa-user-group me-1"></i> CÓ KHÁCH
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <!-- Nút Xem mã QR -->
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1 fw-bold" 
                                        onclick="generateTableQR('{{ $table->id }}', '{{ $table->name }}')"
                                        title="Tạo mã QR đặt món">
                                    <i class="fa-solid fa-qrcode"></i> QR
                                </button>

                                <!-- Nút Sửa -->
                                <button type="button" class="btn-action edit-btn" 
                                        data-id="{{ $table->id }}" 
                                        data-name="{{ $table->name }}"
                                        data-area="{{ $table->area }}" 
                                        data-status="{{ $table->status }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fa-solid fa-pen-to-square text-warning"></i>
                                </button>

                                <!-- Nút Xóa lẻ -->
                                <button type="button" class="btn-action" onclick="deleteTable('{{ $table->id }}')">
                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-25 mb-3"><i class="fa-solid fa-couch fa-4x"></i></div>
                                <h5 class="text-secondary">Chưa có bàn nào trong sơ đồ phục vụ</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL XEM QR (Nền tối đồng bộ) ==================== -->
<div class="modal fade" id="qrTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="qrModalTitle">BÀN 01</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div id="qrContainer" class="bg-white p-3 rounded-4 shadow-sm border border-light mx-auto" style="width: fit-content;"></div>
                <p class="small text-muted mt-3 mb-0">Khách quét mã này để tự đặt món</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn-save-full" onclick="printQR()">
                    <i class="fa-solid fa-print me-2"></i>IN MÃ ĐẶT BÀN
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM BÀN (Nền tối, Nút xanh Full) ==================== -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tables.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">THÊM BÀN MỚI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label>TÊN BÀN <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Bàn 01" required>
                </div>
                <div class="mb-3">
                    <label>KHU VỰC</label>
                    <input type="text" name="area" class="form-control" placeholder="VD: Sân vườn, Lầu 1" required>
                </div>
                <div class="mb-4">
                    <label>TRẠNG THÁI BAN ĐẦU</label>
                    <select name="status" class="form-select">
                        <option value="available">Sẵn sàng phục vụ</option>
                        <option value="occupied">Đang bận</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-save-full">LƯU THIẾT LẬP</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SỬA BÀN (Nền tối, Nút vàng Full) ==================== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" style="color: #ffc107;">CẬP NHẬT THÔNG TIN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label>TÊN BÀN</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>KHU VỰC</label>
                    <input type="text" name="area" id="edit_area" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label>TRẠNG THÁI PHỤC VỤ</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="available">Trống</option>
                        <option value="occupied">Có khách</option>
                        <option value="pending">Chờ xác nhận</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-save-full" style="background-color: #ffc107; color: #1c1917;">LƯU THAY ĐỔI</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const deleteBtn = document.getElementById('btn-delete-selected');
    const countSpan = document.getElementById('selected-count');
    const searchInput = document.getElementById('tableSearchInput');
    const tableRows = document.querySelectorAll('.table-row');
    const visibleTableCount = document.getElementById('visibleTableCount');

    // 1. Logic Tìm kiếm thời gian thực
    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = ""; 
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });
        visibleTableCount.innerText = visibleCount;
    });

    // 2. Logic Chọn hàng & Xóa hàng loạt
    function updateBulkUI() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        countSpan.textContent = checked;
        deleteBtn.classList.toggle('d-none', checked === 0);
    }

    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkUI();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkUI);
    });

    // 3. Xử lý nạp dữ liệu Modal Sửa
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_area').value = this.dataset.area || '';
            document.getElementById('edit_status').value = this.dataset.status;
            document.getElementById('editForm').action = `{{ url('/tables') }}/${this.dataset.id}`;
        });
    });
});

// 4. LOGIC GENERATE QR (Dùng màu vàng thương hiệu)
function generateTableQR(id, name) {
    const orderUrl = `{{ url('/order/table') }}/${id}`;
    // Mã màu hổ phách d97706 được đưa vào API để tạo QR màu vàng đồng bộ
    const qrApi = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(orderUrl)}&bgcolor=ffffff&color=d97706&format=png`;

    document.getElementById('qrModalTitle').innerText = name.toUpperCase();
    document.getElementById('qrContainer').innerHTML = `<img src="${qrApi}" class="img-fluid animate__animated animate__zoomIn" alt="QR Code">`;
    
    bootstrap.Modal.getOrCreateInstance(document.getElementById('qrTableModal')).show();
}

function printQR() {
    const qrImg = document.querySelector('#qrContainer img').src;
    const win = window.open('', '_blank');
    win.document.write(`
        <div style="text-align:center; font-family: sans-serif; padding: 40px; border: 2px dashed #ccc; border-radius: 20px;">
            <h1 style="color: #d97706; margin-bottom: 5px;">HUTECH COFFEE</h1>
            <p style="color: #666; margin-top: 0;">SINCE 2026</p>
            <hr style="width: 50%; border: 1px solid #eee;">
            <h2 style="margin: 20px 0;">${document.getElementById('qrModalTitle').innerText}</h2>
            <img src="${qrImg}" style="width:250px;">
            <p style="font-weight: bold; margin-top: 20px;">QUÉT MÃ ĐỂ ĐẶT MÓN</p>
            <p style="font-size: 12px; color: #888;">Cảm ơn quý khách đã sử dụng dịch vụ</p>
        </div>
    `);
    win.print();
    win.close();
}

// 5. Thao tác xóa đơn lẻ
function deleteTable(id) {
    if(confirm('Bạn có chắc chắn muốn xóa bàn này khỏi hệ thống?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/tables') }}/${id}`;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmBulkDelete() {
    if (confirm(`Bạn muốn xóa TOÀN BỘ các bàn đã chọn?`)) {
        document.getElementById('bulk-delete-form').submit();
    }
}
</script>

@endsection