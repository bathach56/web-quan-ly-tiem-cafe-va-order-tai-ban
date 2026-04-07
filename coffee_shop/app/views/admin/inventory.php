<?php require_once '../app/views/inc/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-boxes text-warning me-2"></i> Quản lý Kho Hàng</h2>
        <div>
            <button class="btn btn-outline-primary fw-bold shadow-sm me-2" onclick="openItemModal()">
                <i class="fas fa-plus me-1"></i> Khai báo nguyên liệu
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i> Báo Cáo Nhập - Xuất - Tồn</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Mã NL</th>
                            <th>Tên Nguyên Liệu</th>
                            <th>Đơn vị tính</th>
                            <th class="text-center">Tồn kho hiện tại</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end pe-4">Thao tác Nhanh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['items'])): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu kho. Hãy khai báo nguyên liệu trước.</td></tr>
                        <?php endif; ?>
                        
                        <?php foreach ($data['items'] as $item): ?>
                        <tr>
                            <td class="ps-4 text-muted fw-bold">NL-<?= sprintf('%03d', $item['id']) ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['unit']) ?></td>
                            <td class="text-center fw-bold fs-5 text-primary"><?= $item['current_stock'] ?></td>
                            <td class="text-center">
                                <?php if($item['current_stock'] <= 0): ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php elseif($item['current_stock'] <= $item['min_stock']): ?>
                                    <span class="badge bg-warning text-dark">Sắp hết</span>
                                <?php else: ?>
                                    <span class="badge bg-success">An toàn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-success shadow-sm me-1" onclick="openTransactionModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>', 'in')">
                                    <i class="fas fa-arrow-down"></i> Nhập
                                </button>
                                <button class="btn btn-sm btn-danger shadow-sm" onclick="openTransactionModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['name']) ?>', 'out')">
                                    <i class="fas fa-arrow-up"></i> Xuất
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= URLROOT ?>/inventory/createItem" method="POST">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Khai Báo Nguyên Liệu Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên nguyên liệu / Hàng hóa</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Cà phê hạt hạt, Sữa tươi...">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Đơn vị tính</label>
                            <input type="text" name="unit" class="form-control" required placeholder="Ví dụ: Kg, Lít, Gói...">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Mức cảnh báo (Min)</label>
                            <input type="number" name="min_stock" class="form-control" value="5" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary fw-bold">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= URLROOT ?>/inventory/transaction" method="POST">
                <div class="modal-header text-white" id="transModalHeader">
                    <h5 class="modal-title fw-bold" id="transModalTitle">Tạo Phiếu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="item_id" id="trans_item_id">
                    <input type="hidden" name="type" id="trans_type">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nguyên liệu đang chọn:</label>
                        <input type="text" id="trans_item_name" class="form-control fw-bold text-primary" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số lượng</label>
                        <input type="number" name="quantity" class="form-control" required min="1" placeholder="Nhập số lượng...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú (Lý do / NCC)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Nhập lý do xuất/nhập hoặc tên nhà cung cấp..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn fw-bold text-white" id="transSubmitBtn">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openItemModal() {
        new bootstrap.Modal(document.getElementById('itemModal')).show();
    }

    function openTransactionModal(itemId, itemName, type) {
        document.getElementById('trans_item_id').value = itemId;
        document.getElementById('trans_item_name').value = itemName;
        document.getElementById('trans_type').value = type;
        
        const header = document.getElementById('transModalHeader');
        const btn = document.getElementById('transSubmitBtn');
        const title = document.getElementById('transModalTitle');

        if (type === 'in') {
            title.innerText = 'TẠO PHIẾU NHẬP KHO';
            header.className = 'modal-header bg-success text-white';
            btn.className = 'btn btn-success fw-bold text-white';
            btn.innerText = 'Xác nhận Nhập Kho';
        } else {
            title.innerText = 'TẠO PHIẾU XUẤT KHO';
            header.className = 'modal-header bg-danger text-white';
            btn.className = 'btn btn-danger fw-bold text-white';
            btn.innerText = 'Xác nhận Xuất Kho';
        }

        new bootstrap.Modal(document.getElementById('transactionModal')).show();
    }
</script>

<?php require_once '../app/views/inc/footer.php'; ?>