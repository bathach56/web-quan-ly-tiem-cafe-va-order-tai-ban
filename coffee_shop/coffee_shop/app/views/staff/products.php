<?php require_once '../app/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark fw-bold">
            <i class="fas fa-coffee text-warning"></i> Quản lý Món Ăn
        </h2>
        <div>
            <a href="<?= URLROOT ?>/dashboard" class="btn btn-secondary btn-lg me-2 shadow-sm">
                <i class="fas fa-home"></i> Dashboard
            </a>
            
            <button class="btn btn-success btn-lg me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetModal()">
                <i class="fas fa-plus"></i> Thêm món mới
            </button>
            
            <a href="<?= URLROOT ?>/auth/logout" class="btn btn-danger btn-lg shadow-sm">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="80" class="text-center">Ảnh</th>
                            <th>Tên món</th>
                            <th>Danh mục</th>
                            <th class="text-end">Giá (đ)</th>
                            <th class="text-center">Trạng thái</th>
                            <th width="150" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Chưa có món ăn nào. Hãy thêm món mới!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="text-center">
                                    <img src="<?= ASSET_IMG . htmlspecialchars($p['image']) ?>" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['category_name']) ?></span></td>
                                <td class="text-end fw-bold text-primary"><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                                <td class="text-center">
                                    <?php if($p['status'] == 'active'): ?>
                                        <span class="badge bg-success">Đang bán</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ngừng bán</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm text-dark shadow-sm" onclick="editProduct(<?= htmlspecialchars(json_encode($p)) ?>)">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <a href="<?= URLROOT ?>/product/delete/<?= $p['id'] ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa món: <?= htmlspecialchars($p['name']) ?>?')" 
                                       class="btn btn-danger btn-sm shadow-sm">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= URLROOT ?>/product/save" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitle"><i class="fas fa-plus-circle me-2"></i>Thêm món mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tên món</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên món..." required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Ví dụ: 25000" required min="0" step="1000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active">Đang bán</option>
                                <option value="inactive">Ngừng bán</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh sản phẩm</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="text-muted">Bỏ trống nếu không muốn thay đổi ảnh (khi sửa).</small>
                        <div id="current_img" class="mt-3 text-center"></div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Đóng</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Hàm nạp dữ liệu vào form khi bấm Sửa
function editProduct(product) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Cập nhật món ăn';
    document.getElementById('edit_id').value = product.id;
    document.getElementById('category_id').value = product.category_id;
    document.getElementById('name').value = product.name;
    document.getElementById('price').value = product.price;
    document.getElementById('status').value = product.status;
    
    // Đảm bảo ô chọn file trống khi mở form sửa
    document.getElementById('image').value = '';

    // Hiển thị ảnh hiện hành
    document.getElementById('current_img').innerHTML = `
        <p class="mb-1 text-muted small">Ảnh hiện tại:</p>
        <img src="<?= ASSET_IMG ?>${product.image}" class="img-thumbnail shadow-sm" style="height: 120px; object-fit: cover;">
    `;

    // Mở modal
    new bootstrap.Modal(document.getElementById('productModal')).show();
}

// Hàm dọn dẹp form khi bấm Thêm mới
function resetModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Thêm món mới';
    document.getElementById('edit_id').value = '';
    document.getElementById('category_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('price').value = '';
    document.getElementById('status').value = 'active';
    document.getElementById('image').value = '';
    document.getElementById('current_img').innerHTML = '';
}
</script>

<?php require_once '../app/views/inc/footer.php'; ?>