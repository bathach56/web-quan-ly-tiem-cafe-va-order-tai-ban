<?php require_once '../app/views/inc/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-chart-line text-success me-2"></i> Báo Cáo Doanh Thu</h2>
        
        <div>
            <button onclick="window.print()" class="btn btn-danger fw-bold shadow-sm me-2 d-none d-md-inline-block">
                <i class="fas fa-file-pdf me-1"></i> Xuất PDF
            </button>
            
            <a href="<?= URLROOT ?>/report/exportExcel?start_date=<?= $data['start_date'] ?>&end_date=<?= $data['end_date'] ?>" class="btn btn-success fw-bold shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded">
            <form action="<?= URLROOT ?>/report/index" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Từ ngày:</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $data['start_date'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Đến ngày:</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $data['end_date'] ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary fw-bold w-100 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Lọc dữ liệu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <h5 class="opacity-75 mb-2">TỔNG DOANH THU KỲ BÁO CÁO</h5>
                    <h2 class="fw-bold mb-0"><?= number_format($data['total_revenue'], 0, ',', '.') ?> <small class="fs-5">VNĐ</small></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <h5 class="opacity-75 mb-2">TỔNG SỐ ĐƠN HÀNG</h5>
                    <h2 class="fw-bold mb-0"><?= number_format($data['total_orders']) ?> <small class="fs-5">Đơn</small></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" id="printableTable">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3">Ngày</th>
                            <th class="text-center py-3">Số lượng đơn hàng</th>
                            <th class="text-end pe-4 py-3">Doanh thu (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['report_list'])): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <p>Không có dữ liệu doanh thu trong khoảng thời gian này.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['report_list'] as $row): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?= date('d/m/Y', strtotime($row['order_date'])) ?></td>
                                <td class="text-center fw-semibold"><?= $row['total_orders'] ?></td>
                                <td class="text-end pe-4 fw-bold text-success">
                                    <?= number_format($row['daily_revenue'], 0, ',', '.') ?> đ
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

<style>
    @media print {
        body { background: white; }
        .navbar, .btn, form, footer { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .card-body.bg-success, .card-body.bg-info { background-color: #f8f9fa !important; color: #000 !important; border: 1px solid #000; }
        h2.fw-bold { color: #000 !important; }
    }
</style>

<?php require_once '../app/views/inc/footer.php'; ?>