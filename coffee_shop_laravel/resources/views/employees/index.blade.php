@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng uốn lượn hàng nhân viên */
    .employee-row { transition: all 0.3s ease; }
    .employee-row:hover { 
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

    /* Avatar tròn trong bảng */
    .avatar-circle {
        width: 45px; height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f1f0ef;
    }

    /* --- FIX VIDEO 1: CĂN GIỮA AVATAR TRONG POPUP --- */
    .preview-avatar-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px; 
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .preview-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary);
        background: #3d3935;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    /* Tinh chỉnh Header bảng */
    .table thead th {
        background-color: #ffffff;
        color: #1c1917;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 22px 20px;
        border-bottom: 2px solid #f3f2f1;
    }

    .table tbody td {
        padding: 18px 20px;
        color: #44403c;
        vertical-align: middle;
        border-bottom: 1px solid #f8f7f6;
        font-weight: 500;
    }

    /* Thanh tìm kiếm */
    .search-container {
        border-radius: 20px;
        background: rgba(255,255,255,0.05);
    }
</style>

<div class="container-fluid pb-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold m-0 text-warning">
                <i class="fa-solid fa-users-gear me-2"></i> {{ __('messages.manage_staff') }}
            </h3>
            <p class="text-secondary small m-0 mt-1">{{ __('messages.manage_staff_desc') }}</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-user-plus me-1"></i> {{ __('messages.add_employee') }}
        </button>
    </div>

    <!-- Thanh Tìm Kiếm Real-time -->
    <div class="card border-0 mb-4 shadow-sm search-container">
        <div class="card-body p-3">
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y ms-3 text-secondary"></i>
                <input type="text" id="employeeSearch" class="form-control ps-5 border-0 bg-transparent text-black" placeholder="{{ __('messages.search_employee') }}">
            </div>
        </div>
    </div>

    <!-- Bảng Dữ Liệu Bo Cong 30px -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th class="ps-4">{{ __('messages.avatar') }}</th>
                        <th>{{ __('messages.full_name') }}</th>
                        <th>{{ __('messages.username') }}</th>
                        <th class="text-center">{{ __('messages.status') }}</th>
                        <th class="pe-4 text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    @forelse($employees as $emp)
                    <tr class="employee-row align-middle">
                        @php
                            // FIX LỖI: Định nghĩa biến để dùng cho cả cột Avatar và thuộc tính data của nút Sửa
                            $avatarUrl = $emp->avatar 
                                ? asset('uploads/avatars/' . $emp->avatar) 
                                : 'https://ui-avatars.com/api/?name='.urlencode($emp->name).'&background=d97706&color=fff';
                        @endphp
                        <td class="ps-4">
                            <img src="{{ $avatarUrl }}" class="avatar-circle shadow-sm">
                        </td>
                        <td><div class="fw-bold text-dark fs-6">{{ $emp->name }}</div></td>
                        <td><code class="text-pink">{{ $emp->username }}</code></td>
                        <td class="text-center">
                            @if($emp->status == 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">{{ __('messages.active') }}</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">{{ __('messages.inactive') }}</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <!-- Nút Sửa: Đã nạp đầy đủ data-avatar để fix Video 2 -->
                            <button class="btn-action edit-btn" 
                                    data-id="{{ $emp->id }}"
                                    data-name="{{ $emp->name }}"
                                    data-username="{{ $emp->username }}"
                                    data-position="{{ $emp->position }}"
                                    data-status="{{ $emp->status }}"
                                    data-avatar="{{ $avatarUrl }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen-to-square text-warning"></i>
                            </button>

                            <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action">
                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-secondary">{{ __('messages.no_staff_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM MỚI (Fix Căn Giữa Avatar) ==================== -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.add_new_employee') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="preview-avatar-container">
                    <img id="add_avatar_preview" src="https://ui-avatars.com/api/?name=NV&background=3d3935&color=fff" class="preview-avatar-img">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.employee_photo') }}</label>
                    <input type="file" name="avatar" class="form-control" onchange="previewImage(this, 'add_avatar_preview')">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.full_name') }} *</label>
                    <input type="text" name="name" class="form-control" required placeholder="{{ __('messages.eg_name') }}">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.username') }} *</label>
                    <input type="text" name="username" class="form-control" required placeholder="{{ __('messages.eg_username') }}">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.password_req') }}</label>
                    <input type="password" name="password" class="form-control" required placeholder="{{ __('messages.min_6_chars') }}">
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label>{{ __('messages.role') }}</label>
                        <select name="position" class="form-select">
                            <option value="staff">{{ __('messages.staff') }}</option>
                            <option value="admin">{{ __('messages.admin') }}</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label>{{ __('messages.status') }}</label>
                        <select name="status" class="form-select">
                            <option value="active">{{ __('messages.active') }}</option>
                            <option value="inactive">{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-save-full">{{ __('messages.save_employee') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SỬA (Fix Video 2: Hiện đúng ảnh cũ) ==================== -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editEmployeeForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" style="color: #ffc107;">{{ __('messages.edit_employee_info') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="preview-avatar-container">
                    <img id="edit_avatar_preview" src="" class="preview-avatar-img">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.edit_photo') }}</label>
                    <input type="file" name="avatar" class="form-control" onchange="previewImage(this, 'edit_avatar_preview')">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.full_name') }} *</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.username_fixed') }}</label>
                    <input type="text" id="edit_username" class="form-control" readonly style="opacity: 0.6;">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.new_password_opt') }}</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••">
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label>{{ __('messages.role') }}</label>
                        <select id="edit_position" name="position" class="form-select">
                            <option value="staff">{{ __('messages.staff') }}</option>
                            <option value="admin">{{ __('messages.admin') }}</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label>{{ __('messages.status') }}</label>
                        <select id="edit_status" name="status" class="form-select">
                            <option value="active">{{ __('messages.active') }}</option>
                            <option value="inactive">{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-save-full" style="background-color: #ffc107; color: #1c1917;">{{ __('messages.update_changes') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
// Hàm preview ảnh khi chọn file
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { document.getElementById(previewId).src = e.target.result; }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function() {
    // 1. Tìm kiếm Real-time
    $("#employeeSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#employeeTableBody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // 2. FIX ICON SỬA: Dùng Event Delegation $(document).on
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const username = $(this).data('username');
        const position = $(this).data('position');
        const status = $(this).data('status');
        const avatar = $(this).data('avatar'); // Link ảnh từ data-avatar

        $('#edit_name').val(name);
        $('#edit_username').val(username);
        $('#edit_position').val(position);
        $('#edit_status').val(status);
        
        // --- FIX VIDEO 2: Nạp đúng ảnh hiện tại của nhân viên đó vào Modal ---
        $('#edit_avatar_preview').attr('src', avatar);

        $('#editEmployeeForm').attr('action', '/employees/' + id);
    });
});
</script>
@endsection