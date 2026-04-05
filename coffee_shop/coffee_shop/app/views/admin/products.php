<?php require_once '../app/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-coffee text-warning"></i> Quản lý Sản phẩm
        </h2>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fas fa-plus"></i> Thêm món mới
        </button>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['flash'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Bảng danh sách sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="80">Ảnh</th>
                            <th>Tên món</th>
                            <th>Danh mục</th>
                            <th class="text-end">Giá (đ)</th>
                            <th>Trạng thái</th>
                            <th width="180">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?= ASSET_IMG . htmlspecialchars($product['image']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>"
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                <td class="text-end fw-bold text-primary">
                                    <?= number_format($product['price'], 0, ',', '.') ?> đ
                                </td>
                                <td>
                                    <?php if ($product['status'] == 'active'): ?>
                                        <span class="badge bg-success">Đang bán</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ngừng bán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)" 
                                            class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <a href="<?= URLROOT ?>/product/delete/<?= $product['id'] ?>" 
                                       onclick="return confirm('Bạn chắc chắn muốn xóa món này?')"
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL THÊM / SỬA SẢN PHẨM ==================== -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= URLROOT ?>/product/save" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm món mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="product_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tên món</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá (VNĐ)</label>
                                <input type="number" name="price" id="price" class="form-control" required min="1000" step="1000">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ảnh món</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="text-muted">Chỉ hỗ trợ JPG, JPEG, PNG</small>
                                <div id="currentImage" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="active">Đang bán</option>
                                    <option value="inactive">Ngừng bán</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu món</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript xử lý modal Edit
function editProduct(product) {
    document.getElementById('modalTitle').textContent = 'Sửa món';
    document.getElementById('product_id').value = product.id;
    document.getElementById('category_id').value = product.category_id;
    document.getElementById('name').value = product.name;
    document.getElementById('price').value = product.price;
    document.getElementById('status').value = product.status;
    document.getElementById('description').value = product.description || '';

    // Hiển thị ảnh hiện tại
    const currentImageDiv = document.getElementById('currentImage');
    currentImageDiv.innerHTML = `
        <p class="mb-1">Ảnh hiện tại:</p>
        <img src="<?= ASSET_IMG ?>${product.image}" 
             style="max-height: 120px; border-radius: 8px;">
    `;

    // Mở modal
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

// Reset modal khi đóng
document.getElementById('productModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalTitle').textContent = 'Thêm món mới';
    document.getElementById('product_id').value = '';
    document.getElementById('image').value = '';
    document.getElementById('currentImage').innerHTML = '';
});
</script>

<?php require_once '../app/views/inc/header.php'; ?>  <!-- Footer -->