@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng uốn lượn chuyên nghiệp cho hàng */
    .product-row {
        transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
    }
    
    .product-row:hover {
        background: rgba(217, 119, 6, 0.04) !important;
        transform: scale(1.002);
    }

    /* Bo góc cho ảnh món ăn - FIX lỗi mất đầu mất đuôi */
    .product-img-wrapper {
        width: 55px;
        height: 55px;
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid #f1f0ef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        background-color: #f9f8f7; /* Nền nhẹ cho ảnh contain */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Đảm bảo hiện đủ hình */
    }

    .price-text {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        color: #1c1917;
        font-size: 1rem;
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

    /* Badge trạng thái */
    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }
</style>

<div class="container-fluid pb-4">
    <!-- Header Section - Tiêu đề màu vàng -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 text-warning">
                <i class="fa-solid fa-mug-hot me-2"></i> QUẢN LÝ THỰC ĐƠN
            </h3>
            <p class="text-secondary small m-0 mt-1">Cập nhật hình ảnh, giá bán và danh mục các món ăn tại quán.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-1"></i> THÊM MÓN MỚI
        </button>
    </div>

    <!-- Thanh Tìm Kiếm & Thống kê nhanh -->
    <div class="card border-0 mb-4 shadow-sm" style="border-radius: 20px; background: rgba(255,255,255,0.05);">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="fa-solid fa-magnifying-glass search-icon-wrapper"></i>
                        <input type="text" id="searchInput" class="form-control search-box" 
                               placeholder="Tìm tên món, mã món hoặc danh mục...">
                    </div>
                </div>
                <div class="col-md-7 text-md-end mt-2 mt-md-0">
                    <span class="text-secondary small fw-bold">Đang hiển thị: <span id="visibleCount" class="text-warning">{{ count($products) }}</span> sản phẩm</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng Dữ Liệu Bo Cong 30px (Card trắng) -->
    <div class="card shadow-lg">
        <div class="table-responsive">
            <table class="table mb-0" id="productTable">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 80px;">ẢNH</th>
                        <th>TÊN MÓN ĂN</th>
                        <th>DANH MỤC</th>
                        <th class="text-end">GIÁ BÁN</th>
                        <th class="text-center">TRẠNG THÁI</th>
                        <th class="pe-4 text-end">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="product-row">
                        <td class="ps-4">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('img/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/100x100?text=Coffee'">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $product->name }}</div>
                            <small class="text-muted">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-3 py-2">
                                {{ $product->category->name ?? 'Chưa phân loại' }}
                            </span>
                        </td>
                        <td class="text-end price-text pe-3">
                            {{ number_format($product->price) }}đ
                        </td>
                        <td class="text-center">
                            @if($product->status == 'active')
                                <span class="status-badge bg-success bg-opacity-10 text-success border-success border-opacity-25">
                                    <i class="fa-solid fa-circle-check me-1"></i> Đang bán
                                </span>
                            @else
                                <span class="status-badge bg-danger bg-opacity-10 text-danger border-danger border-opacity-25">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> Tạm dừng
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn-action edit-button"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    data-category="{{ $product->category_id }}"
                                    data-status="{{ $product->status }}"
                                    data-image="{{ asset('img/' . $product->image) }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen-to-square text-warning"></i>
                            </button>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa món này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action">
                                    <i class="fa-solid fa-trash-can text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-5">
                            <div class="opacity-25 mb-3"><i class="fa-solid fa-box-open fa-4x"></i></div>
                            <h5 class="text-secondary">Thực đơn hiện đang trống</h5>
                        </td>
                    </tr>
                    @endforelse
                    
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-magnifying-glass fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Không tìm thấy món nào khớp với từ khóa của bạn.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM MÓN (Popup nền tối đồng bộ) ==================== -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">THÊM MÓN MỚI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="mb-3">
                    <label>TÊN MÓN ĂN <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="VD: Bạc xỉu cốt dừa">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label>DANH MỤC</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label>GIÁ BÁN (VNĐ)</label>
                        <input type="number" name="price" class="form-control" required placeholder="35000">
                    </div>
                </div>
                <div class="mb-4">
                    <label>HÌNH ẢNH SẢN PHẨM</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                
                <button type="submit" class="btn-save-full">LƯU THỰC ĐƠN</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SỬA MÓN (Popup nền tối đồng bộ) ==================== -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" style="color: #ffc107;">CẬP NHẬT MÓN ĂN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-3">
                    <label>TÊN MÓN ĂN</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label>DANH MỤC</label>
                        <select id="edit_category" name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label>GIÁ BÁN (VNĐ)</label>
                        <input type="number" id="edit_price" name="price" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>TRẠNG THÁI KINH DOANH</label>
                    <select id="edit_status" name="status" class="form-select">
                        <option value="active">Đang kinh doanh</option>
                        <option value="inactive">Tạm ngừng bán</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>THAY ĐỔI HÌNH ẢNH</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="text-center bg-dark bg-opacity-25 p-3 rounded-4 mb-4">
                    <p class="small fw-bold text-muted mb-2">ẢNH HIỆN TẠI</p>
                    <img id="current_image" src="" class="rounded-3 shadow-sm" width="100" height="100" style="object-fit: contain; background: white; padding: 5px;">
                </div>

                <button type="submit" class="btn-save-full">LƯU THAY ĐỔI</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Logic Tìm kiếm thời gian thực
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.product-row');
    const noResultsRow = document.getElementById('noResultsRow');
    const visibleCount = document.getElementById('visibleCount');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase().trim();
        let matchCount = 0;

        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = ""; 
                matchCount++;
            } else {
                row.style.display = "none";
            }
        });

        visibleCount.innerText = matchCount;
        noResultsRow.style.display = (matchCount === 0 && tableRows.length > 0) ? "" : "none";
    });

    // 2. Logic nạp dữ liệu vào Modal Sửa
    const editButtons = document.querySelectorAll('.edit-button');
    const editForm = document.getElementById('editForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_price').value = this.dataset.price;
            document.getElementById('edit_category').value = this.dataset.category;
            document.getElementById('edit_status').value = this.dataset.status;
            document.getElementById('current_image').src = this.dataset.image;

            editForm.action = `{{ url('products') }}/${this.dataset.id}`;
        });
    });
});
</script>

@endsection