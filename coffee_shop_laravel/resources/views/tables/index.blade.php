@extends('layouts.admin')

@section('content')
<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fa-solid fa-couch me-2 text-primary"></i> Quản lý Sơ đồ bàn
            </h3>
            <p class="text-secondary small mb-0">
                Thiết lập bàn và tạo mã QR Order cho khách tại <b>HUTECH Coffee</b>.
            </p>
        </div>
        
        <div class="d-flex gap-2">
            <button id="btn-delete-selected" class="btn btn-danger px-3 d-none" onclick="confirmBulkDelete()">
                <i class="fa-solid fa-trash me-1"></i> Xóa đã chọn (<span id="selected-count">0</span>)
            </button>

            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fa-solid fa-plus me-1"></i> Thêm Bàn Mới
            </button>
        </div>
    </div>

    <div class="table-container shadow-sm bg-card p-3 rounded-4 border border-secondary border-opacity-10">
        <form id="bulk-delete-form" action="{{ route('tables.bulk_destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="table-responsive">
                <table class="table custom-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th>TÊN BÀN</th>
                            <th>KHU VỰC</th>
                            <th class="text-center">TRẠNG THÁI</th>
                            <th class="text-end pe-4">THIẾT LẬP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tables as $table)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $table->id }}" class="row-checkbox form-check-input">
                            </td>
                            <td>
                                <div class="fw-bold text-warning">{{ $table->name }}</div>
                                <small class="text-muted">ID: #{{ $table->id }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2">
                                    {{ $table->area ?? 'Chưa phân khu' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($table->status == 'available')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">TRỐNG</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3">CÓ KHÁCH</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info me-2 rounded-pill px-3" 
                                            onclick="generateTableQR('{{ $table->id }}', '{{ $table->name }}')"
                                            title="Tạo mã QR đặt món">
                                        <i class="fa-solid fa-qrcode"></i> QR
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-warning me-2 edit-btn rounded-circle"
                                            data-id="{{ $table->id }}" data-name="{{ $table->name }}"
                                            data-area="{{ $table->area }}" data-status="{{ $table->status }}"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" 
                                            onclick="deleteTable('{{ $table->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-couch fa-3x mb-3 opacity-25"></i>
                                <p>Chưa có bàn nào trong sơ đồ.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="qrTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-800 w-100 text-warning" id="qrModalTitle">BÀN 01</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div id="qrContainer" class="bg-white p-3 rounded-4 shadow-sm border border-light mx-auto" style="width: fit-content;">
                    </div>
                <p class="small text-muted mt-3 mb-0">Khách quét mã này để tự đặt món</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-primary w-100 rounded-pill fw-bold" onclick="printQR()">
                    <i class="fa-solid fa-print me-2"></i>In mã đặt bàn
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tables.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Bàn Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Tên bàn</label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Bàn 01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Khu vực</label>
                    <input type="text" name="area" class="form-control" placeholder="VD: Sân vườn, Lầu 1" required>
                </div>
                <div>
                    <label class="form-label fw-bold small text-uppercase">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="available">Sẵn sàng phục vụ</option>
                        <option value="occupied">Đang bận</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4">Lưu bàn</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Chỉnh sửa thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Tên bàn</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase">Khu vực</label>
                    <input type="text" name="area" id="edit_area" class="form-control" required>
                </div>
                <div>
                    <label class="form-label fw-bold small text-uppercase">Trạng thái</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="available">Trống</option>
                        <option value="occupied">Có khách</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold">Cập nhật thay đổi</button>
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

    function updateUI() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        countSpan.textContent = checked;
        deleteBtn.classList.toggle('d-none', checked === 0);
    }

    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateUI();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateUI);
    });

    // Xử lý nạp dữ liệu Modal Sửa
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

// LOGIC GENERATE QR (Dùng API miễn phí cực nhanh)
function generateTableQR(id, name) {
    // URL dẫn đến route đặt món của bàn: http://localhost:8000/order/table/{id}
    const orderUrl = `{{ url('/order/table') }}/${id}`;
    
    // Sử dụng API qrserver để lấy ảnh QR
    const qrApi = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(orderUrl)}&bgcolor=ffffff&color=d97706&format=png`;

    document.getElementById('qrModalTitle').innerText = name.toUpperCase();
    document.getElementById('qrContainer').innerHTML = `<img src="${qrApi}" class="img-fluid animate__animated animate__zoomIn" alt="QR Code">`;
    
    new bootstrap.Modal(document.getElementById('qrTableModal')).show();
}

function printQR() {
    const qrImg = document.querySelector('#qrContainer img').src;
    const win = window.open('', '_blank');
    win.document.write(`
        <div style="text-align:center; font-family: sans-serif; padding: 40px; border: 2px dashed #ccc;">
            <h1 style="color: #d97706;">HUTECH COFFEE</h1>
            <h2>${document.getElementById('qrModalTitle').innerText}</h2>
            <img src="${qrImg}" style="width:300px;">
            <p>Vui lòng quét mã để đặt món</p>
        </div>
    `);
    win.print();
    win.close();
}

function deleteTable(id) {
    if(confirm('Chắc chắn muốn xóa bàn này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/tables') }}/${id}`;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmBulkDelete() {
    if (confirm(`Xóa toàn bộ các bàn đã chọn?`)) {
        document.getElementById('bulk-delete-form').submit();
    }
}
</script>

@endsection