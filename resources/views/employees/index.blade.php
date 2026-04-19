@extends('layouts.admin')

@section('content')
<style>
    .employee-row { transition: all 0.3s ease; }
    .employee-row:hover { background: rgba(217, 119, 6, 0.05) !important; }
    
    .avatar-circle {
        width: 45px; 
        height: 45px; 
        border-radius: 50%;
        object-fit: cover; 
        border: 2px solid #eee;
    }
    
    .btn-edit-custom { color: #ffc107; border-color: #ffc107; }
    .btn-edit-custom:hover { background: #ffc107; color: #fff; }
</style>

<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-user-tie me-2 text-primary"></i> Quản lý Nhân sự</h3>
            <p class="text-secondary small mb-0">Quản lý thông tin, tài khoản và ảnh thẻ nhân viên.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm Nhân Viên
        </button>
    </div>

    @if(session('success'))
    <div id="success-alert" class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="table-container shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light text-uppercase small fw-bold">
                    <tr>
                        <th style="width: 80px;" class="ps-4">Avatar</th>
                        <th>Họ và Tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr class="employee-row">
                        <td class="ps-4">
                            @if($employee->avatar)
                                <img src="{{ asset('uploads/avatars/' . $employee->avatar) }}" class="avatar-circle" alt="Avatar">
                            @else
                                <div class="d-inline-flex align-items-center justify-content-center text-white fw-bold rounded-circle bg-warning" style="width:45px;height:45px;">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $employee->name }}</td>
                        <td><code>{{ $employee->username }}</code></td>
                        <td>
                            <span class="badge {{ $employee->position === 'Admin' ? 'bg-danger' : 'bg-primary' }} rounded-pill px-3">
                                {{ $employee->position ?? 'Nhân viên' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($employee->status == 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3">Tạm khóa</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-warning me-1 edit-btn"
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}"
                                    data-username="{{ $employee->username }}"
                                    data-position="{{ $employee->position ?? 'Staff' }}"
                                    data-status="{{ $employee->status ?? 'active' }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa nhân viên này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có dữ liệu nhân viên.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ====================== MODAL THÊM NHÂN VIÊN ====================== -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Thêm Nhân Viên Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <div class="mb-3 text-center">
                    <img id="add_preview" src="https://ui-avatars.com/api/?name=NV&background=d97706&color=fff" class="rounded-circle border mb-2 shadow-sm" width="90" height="90">
                    <input type="file" name="avatar" class="form-control form-control-sm mt-2" accept="image/*" onchange="previewImg(this, 'add_preview')">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Họ và Tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control shadow-none" required minlength="6">
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small text-dark">Vai trò</label>
                        <select name="position" class="form-select shadow-none">
                            <option value="Staff">Nhân viên</option>
                            <option value="Barista">Pha chế</option>
                            <option value="Admin">Quản trị viên</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small text-dark">Trạng thái</label>
                        <select name="status" class="form-select shadow-none">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Tạm khóa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4 fw-bold">Lưu nhân viên</button>
            </div>
        </form>
    </div>
</div>

<!-- ====================== MODAL SỬA NHÂN VIÊN ====================== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editEmployeeForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Sửa Thông Tin Nhân Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <input type="hidden" id="edit_id" name="id">

                <div class="mb-3 text-center">
                    <img id="edit_preview" src="https://ui-avatars.com/api/?name=NV&background=d97706&color=fff" 
                         class="rounded-circle border mb-2 shadow-sm" width="90" height="90">
                    <input type="file" id="edit_avatar" name="avatar" class="form-control form-control-sm mt-2" 
                           accept="image/*" onchange="previewImg(this, 'edit_preview')">
                    <small class="text-muted d-block mt-1">Để trống nếu không muốn thay ảnh</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                    <input type="text" id="edit_username" name="username" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Mật khẩu mới (để trống nếu không đổi)</label>
                    <input type="password" id="edit_password" name="password" class="form-control" 
                           placeholder="Nhập mật khẩu mới nếu muốn đổi">
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Chức vụ</label>
                        <select id="edit_position" name="position" class="form-select">
                            <option value="Admin">Admin</option>
                            <option value="Staff">Nhân viên phục vụ</option>
                            <option value="Barista">Pha chế</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select id="edit_status" name="status" class="form-select">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Tạm khóa</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-warning">Cập nhật nhân viên</button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview ảnh
function previewImg(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(imgId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const editForm = document.getElementById('editEmployeeForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id        = this.dataset.id;
            const name      = this.dataset.name;
            const username  = this.dataset.username;
            const position  = this.dataset.position;
            const status    = this.dataset.status;

            // Điền dữ liệu vào form
            document.getElementById('edit_id').value       = id;
            document.getElementById('edit_name').value     = name;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_position').value = position;
            document.getElementById('edit_status').value   = status;

            // Reset preview về avatar hiện tại hoặc mặc định
            const currentAvatar = this.dataset.avatar || 'https://ui-avatars.com/api/?name=NV&background=d97706&color=fff';
            document.getElementById('edit_preview').src = currentAvatar;

            // Set action URL quan trọng nhất
            editForm.action = `/employees/${id}`;

            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });

    // Tự động ẩn alert success
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove('show');
            setTimeout(() => successAlert.remove(), 500);
        }, 2500);
    }
});
</script>

@endsection