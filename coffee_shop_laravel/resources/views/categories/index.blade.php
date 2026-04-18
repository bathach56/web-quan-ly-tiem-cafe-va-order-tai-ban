@extends('layouts.admin')

@section('content')
<style>
    .category-tab {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        color: var(--text-gray);
        cursor: pointer;
        user-select: none;
    }

    .category-tab:hover {
        background: rgba(217, 119, 6, 0.08);
        transform: translateY(-2px);
    }

    .category-tab.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 15px -2px rgba(217, 119, 6, 0.4);
    }

    .category-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
    }

    .table-container {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 dynamic-text">
                <i class="fa-solid fa-layer-group me-2"></i> Quản lý Danh mục
            </h3>
            <p class="text-secondary small mb-0">Phân loại thực đơn để nhân viên POS dễ thao tác.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục
        </button>
    </div>

    <!-- Danh sách danh mục với hiệu ứng -->
    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>TÊN DANH MỤC</th>
                        <th>MÔ TẢ</th>
                        <th class="text-center">TRẠNG THÁI</th>
                        <th class="text-end pe-4">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="category-row">
                        <td class="text-secondary fw-bold">#{{ $category->id }}</td>
                        <td>
                            <span class="fw-medium">{{ $category->name }}</span>
                        </td>
                        <td class="text-secondary small">
                            {{ $category->description ?? 'Không có mô tả' }}
                        </td>
                        <td class="text-center">
                            @if($category->status == 'active')
                                <span class="badge bg-success px-3 py-2">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-warning me-2 edit-btn"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-desc="{{ $category->description ?? '' }}"
                                    data-status="{{ $category->status }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa danh mục này sẽ ảnh hưởng đến các món ăn liên quan. Bạn chắc chắn chứ?')">
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
                        <td colspan="5" class="text-center py-5">
                            <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-30"></i>
                            <p class="mb-0 text-secondary">Chưa có danh mục nào. Hãy tạo danh mục mới!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Danh mục -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Trà trái cây">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả ngắn</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Thông tin thêm về danh mục..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active">Hiển thị trên POS</option>
                        <option value="inactive">Tạm ẩn</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu danh mục</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa Danh mục -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Chỉnh sửa Danh Mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên danh mục</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea id="edit_desc" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div>
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select id="edit_status" name="status" class="form-select">
                        <option value="active">Hiển thị trên POS</option>
                        <option value="inactive">Tạm ẩn</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
// Hiệu ứng khi click sửa
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const editForm = document.getElementById('editForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_desc').value = this.dataset.desc || '';
            document.getElementById('edit_status').value = this.dataset.status;

            editForm.action = `/categories/update/${this.dataset.id}`;
        });
    });

    // Hiệu ứng row khi hover
    document.querySelectorAll('.category-row').forEach(row => {
        row.addEventListener('mouseenter', () => row.style.backgroundColor = 'rgba(217, 119, 6, 0.05)');
        row.addEventListener('mouseleave', () => row.style.backgroundColor = '');
    });
});
</script>

@endsection