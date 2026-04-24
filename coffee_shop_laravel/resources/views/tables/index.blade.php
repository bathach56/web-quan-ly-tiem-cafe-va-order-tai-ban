@extends('layouts.admin')

@section('content')
<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fa-solid fa-couch me-2 text-primary"></i> Quản lý Sơ đồ bàn
            </h3>
            <p class="text-secondary small mb-0">
                Thiết lập danh sách bàn cho các khu vực trong quán.
            </p>
        </div>
        
        <div class="d-flex gap-2">
            <!-- Nút xóa nhiều -->
            <button id="btn-delete-selected" 
                    class="btn btn-danger px-3 d-none"
                    onclick="confirmBulkDelete()">
                <i class="fa-solid fa-trash me-1"></i> 
                Xóa đã chọn (<span id="selected-count">0</span>)
            </button>

            <!-- Nút thêm bàn mới -->
            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fa-solid fa-plus me-1"></i> Thêm Bàn Mới
            </button>
        </div>
    </div>

    <div class="table-container shadow-sm">
        <form id="bulk-delete-form" action="{{ route('tables.bulk_destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th>TÊN BÀN</th>
                            <th>KHU VỰC</th>
                            <th class="text-center">TRẠNG THÁI MẶC ĐỊNH</th>
                            <th class="text-end pe-4">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tables as $table)
                        <tr>
                            <td>
                                <input type="checkbox" 
                                       name="ids[]" 
                                       value="{{ $table->id }}" 
                                       class="row-checkbox form-check-input">
                            </td>
                            <td class="fw-medium">{{ $table->name }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $table->area ?? 'Chưa phân khu' }}</span>
                            </td>
                            <td class="text-center">
                                @if($table->status == 'empty')
                                    <span class="badge bg-success">Sẵn sàng</span>
                                @elseif($table->status == 'occupied')
                                    <span class="badge bg-warning">Đang có khách</span>
                                @else
                                    <span class="badge bg-info">Đang chờ</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning me-2 edit-btn"
                                        data-id="{{ $table->id }}"
                                        data-name="{{ $table->name }}"
                                        data-area="{{ $table->area }}"
                                        data-status="{{ $table->status }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('tables.destroy', $table->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa bàn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-couch fa-3x mb-3 opacity-25"></i>
                                <p>Chưa có bàn nào. Hãy thêm bàn mới!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL THÊM BÀN ==================== -->
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
                    <label class="form-label fw-bold">Tên bàn <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="VD: Bàn 01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Khu vực <span class="text-danger">*</span></label>
                    <input type="text" name="area" class="form-control" placeholder="VD: Tầng trệt, Lầu 1, Sân vườn" required>
                </div>
                <div>
                    <label class="form-label fw-bold">Trạng thái mặc định</label>
                    <select name="status" class="form-select">
                        <option value="empty">Sẵn sàng (Empty)</option>
                        <option value="occupied">Đang có khách (Occupied)</option>
                        <option value="waiting">Đang chờ (Waiting)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu bàn</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SỬA BÀN ==================== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Chỉnh sửa bàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên bàn</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Khu vực</label>
                    <input type="text" name="area" id="edit_area" class="form-control" required>
                </div>
                <div>
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="empty">Sẵn sàng</option>
                        <option value="occupied">Đang có khách</option>
                        <option value="waiting">Đang chờ</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== XỬ LÝ CHECKBOX & BULK DELETE ====================
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const deleteBtn = document.getElementById('btn-delete-selected');
    const countSpan = document.getElementById('selected-count');
    const form = document.getElementById('bulk-delete-form');

    function updateUI() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        countSpan.textContent = checked;
        deleteBtn.classList.toggle('d-none', checked === 0);
    }

    // Chọn tất cả
    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateUI();
    });

    // Chọn từng dòng
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateUI);
    });

    // Xác nhận xóa nhiều
    window.confirmBulkDelete = function () {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount === 0) return;

        if (confirm(`Bạn chắc chắn muốn xóa ${checkedCount} bàn đã chọn?`)) {
            form.submit();
        }
    };

    // Đổ dữ liệu vào modal sửa
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_area').value = this.dataset.area || '';
            document.getElementById('edit_status').value = this.dataset.status || 'empty';
            
            document.getElementById('editForm').action = `/tables/${this.dataset.id}`;
        });
    });
});
</script>

@endsection