@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng uốn lượn mượt mà cho hàng */
    .category-row {
        transition: all 0.3s ease;
    }
    
    .category-row:hover {
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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold m-0 text-warning">
                <i class="fa-solid fa-layer-group me-2"></i> {{ __('messages.manage_categories') }}
            </h3>
            <p class="text-secondary small m-0 mt-1">{{ __('messages.manage_categories_desc') }}</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-1"></i> {{ __('messages.add_new_category') }}
        </button>
    </div>

    <!-- Thanh Tìm Kiếm -->
    <div class="card border-0 mb-4 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,0.05);">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="fa-solid fa-magnifying-glass search-icon-wrapper"></i>
                        <input type="text" id="catSearch" class="form-control search-box" placeholder="{{ __('messages.search_category') }}">
                    </div>
                </div>
                <div class="col-md-7 text-md-end mt-2 mt-md-0">
                    <span class="text-secondary small fw-bold">{{ __('messages.total') }} <span id="catCount" class="text-warning">{{ count($categories) }}</span> {{ __('messages.categories_count') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng Dữ Liệu Bo Cong 30px -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table text-nowrap" id="categoryTable" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th style="width: 100px;">{{ __('messages.id') }}</th>
                        <th>{{ __('messages.category_name') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th class="text-center">{{ __('messages.status') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="category-row">
                        <td class="fw-bold text-muted">#{{ $category->id }}</td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $category->name }}</div>
                        </td>
                        <td>
                            <span class="text-secondary small italic">{{ $category->description ?? __('messages.no_description') }}</span>
                        </td>
                        <td class="text-center">
                            @if($category->status == 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                    <i class="fa-solid fa-eye me-1"></i> {{ __('messages.visible') }}
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                                    <i class="fa-solid fa-eye-slash me-1"></i> {{ __('messages.hidden') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn-action edit-btn" 
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-desc="{{ $category->description ?? '' }}"
                                    data-status="{{ $category->status }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen-to-square text-warning"></i>
                            </button>

                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete_category') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action">
                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-25 mb-3"><i class="fa-solid fa-folder-open fa-4x"></i></div>
                            <h5 class="text-secondary">{{ __('messages.no_categories') }}</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM DANH MỤC (Nền tối, Nút xanh Full) ==================== -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.add_category_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label>{{ __('messages.category_name_req') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="{{ __('messages.eg_category_name') }}">
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.desc_label') }}</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="{{ __('messages.desc_placeholder') }}"></textarea>
                </div>
                <div class="mb-4">
                    <label>{{ __('messages.display_status') }}</label>
                    <select name="status" class="form-select">
                        <option value="active">{{ __('messages.status_visible') }}</option>
                        <option value="inactive">{{ __('messages.status_hidden') }}</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-save-full">{{ __('messages.save_category') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SỬA DANH MỤC (Nền tối, Nút xanh Full) ==================== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" style="color: #ffc107;">{{ __('messages.update_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-3">
                    <label>{{ __('messages.category_name_req') }}</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>{{ __('messages.desc_label') }}</label>
                    <textarea id="edit_desc" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-4">
                    <label>{{ __('messages.status') }}</label>
                    <select id="edit_status" name="status" class="form-select">
                        <option value="active">{{ __('messages.status_active_cat') }}</option>
                        <option value="inactive">{{ __('messages.status_inactive_cat') }}</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-save-full" style="background-color: #ffc107; color: #1c1917;">{{ __('messages.save_changes') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Xử lý nạp dữ liệu vào Modal Sửa
    const editButtons = document.querySelectorAll('.edit-btn');
    const editForm = document.getElementById('editForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_desc').value = this.dataset.desc;
            document.getElementById('edit_status').value = this.dataset.status;

            editForm.action = `{{ url('categories') }}/${this.dataset.id}`;
        });
    });

    // 2. Chức năng Tìm kiếm thời gian thực (Real-time Search)
    const searchInput = document.getElementById('catSearch');
    const tableRows = document.querySelectorAll('.category-row');
    const catCount = document.getElementById('catCount');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleRows = 0;

        tableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            if (rowText.includes(query)) {
                row.style.display = "";
                visibleRows++;
            } else {
                row.style.display = "none";
            }
        });
        catCount.innerText = visibleRows;
    });
});
</script>

@endsection