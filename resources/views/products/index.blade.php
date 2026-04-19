@extends('layouts.admin')

@section('content')
<style>
    /* Hiệu ứng chuyên nghiệp cho trang Quản lý Thực đơn */
    .product-row {
        transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
    }
    
    .product-row:hover {
        background: rgba(217, 119, 6, 0.06) !important;
        transform: translateX(8px);
        box-shadow: 0 6px 16px -6px rgba(217, 119, 6, 0.25);
    }

    .product-img-wrapper {
        width: 58px;
        height: 58px;
        overflow: hidden;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-row:hover .product-img-wrapper img {
        transform: scale(1.08);
    }

    .price-text {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        color: var(--primary);
        font-size: 1.05rem;
    }

    .status-badge {
        padding: 8px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 dynamic-text">
                <i class="fa-solid fa-utensils me-2"></i> Quản lý Thực đơn
            </h3>
            <p class="text-secondary small m-0 mt-1">
                Quản lý tất cả món ăn, giá cả và hình ảnh phục vụ kinh doanh.
            </p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm Món Mới
        </button>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 80px;">ẢNH</th>
                        <th>TÊN MÓN</th>
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
                                     onerror="this.src='https://placehold.co/60x60/ddd/666?text=No+Image'">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                {{ $product->category->name ?? 'Chưa phân loại' }}
                            </span>
                        </td>
                        <td class="text-end price-text">
                            {{ number_format($product->price) }} đ
                        </td>
                        <td class="text-center">
                            @if($product->status == 'active')
                                <span class="status-badge border-success text-success bg-success bg-opacity-10">
                                    <i class="fa-solid fa-check-circle"></i> Đang bán
                                </span>
                            @else
                                <span class="status-badge border-danger text-danger bg-danger bg-opacity-10">
                                    <i class="fa-solid fa-ban"></i> Tạm ngưng
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-outline-warning me-2 edit-button"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    data-category="{{ $product->category_id }}"
                                    data-status="{{ $product->status }}"
                                    data-image="{{ asset('img/' . $product->image) }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa món này không?')">
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
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-utensils fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Thực đơn hiện đang trống. Hãy thêm món đầu tiên!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM MÓN ==================== -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-gray);">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Món Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Tên món ăn <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control adaptive-input" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Danh mục</label>
                            <select name="category_id" class="form-select adaptive-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control adaptive-input" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="small fw-bold mb-1">Hình ảnh món ăn</label>
                        <input type="file" name="image" class="form-control adaptive-input" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4">Lưu món mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL SỬA MÓN ==================== -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-gray);">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-warning">Chỉnh sửa Món ăn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Tên món ăn</label>
                        <input type="text" id="edit_name" name="name" class="form-control adaptive-input" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Danh mục</label>
                            <select id="edit_category" name="category_id" class="form-select adaptive-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Giá bán (VNĐ)</label>
                            <input type="number" id="edit_price" name="price" class="form-control adaptive-input" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="small fw-bold mb-1">Trạng thái</label>
                        <select id="edit_status" name="status" class="form-select adaptive-input">
                            <option value="active">Đang kinh doanh</option>
                            <option value="inactive">Tạm ngưng</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="small fw-bold mb-1">Thay đổi hình ảnh</label>
                        <input type="file" name="image" class="form-control adaptive-input" accept="image/*">
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-secondary mb-2">Ảnh hiện tại:</p>
                        <img id="current_image" src="" class="rounded shadow-sm" width="140" style="object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Cập nhật món</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
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

            editForm.action = `/products/update/${this.dataset.id}`;
        });
    });
});
</script>

@endsection