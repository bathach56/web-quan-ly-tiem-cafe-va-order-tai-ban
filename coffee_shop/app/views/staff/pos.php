<?php 
// Trang POS độc lập - KHÔNG dùng header/footer chung
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS - Coffee Shop' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root { var(--primary): #8d5524; }
        * { font-family: 'Segoe UI', system-ui, sans-serif; }
        body { height: 100vh; overflow: hidden; background: #f8f1e3; }
        .pos-container { height: 100vh; display: flex; }
        
        .left-panel { flex: 0 0 62%; display: flex; flex-direction: column; background: white; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 10; }
        .search-bar { padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; }
        .category-tabs { padding: 10px 20px; background: #fff; border-bottom: 1px solid #eee; display: flex; gap: 8px; flex-wrap: wrap; }
        .category-tab { padding: 8px 20px; border-radius: 50px; background: #f1f1f1; cursor: pointer; transition: all 0.2s; font-weight: 500; }
        .category-tab.active { background: #8d5524; color: white; }
        .product-grid { flex: 1; overflow-y: auto; padding: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; align-content: start; }
        .product-card { border: 1px solid #eee; border-radius: 12px; overflow: hidden; transition: all 0.2s; cursor: pointer; background: #fff; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(141, 85, 36, 0.2); border-color: #8d5524; }
        .product-card img { height: 100px; object-fit: cover; }
        
        .right-panel { flex: 0 0 38%; background: #2c2c2c; color: white; display: flex; flex-direction: column; }
        .order-header { background: #1f1f1f; padding: 10px 15px; border-bottom: 1px solid #444; }
        .order-body { flex: 1; overflow-y: auto; padding: 15px; }
        .order-item { background: #3a3a3a; border-radius: 8px; padding: 10px; margin-bottom: 8px; border-left: 4px solid #8d5524; }
        .order-footer { background: #1f1f1f; padding: 15px; border-top: 1px solid #444; }
        
        .total-line { display: flex; justify-content: space-between; font-size: 0.95rem; margin-bottom: 4px; }
        .grand-total { font-size: 1.3rem; font-weight: bold; color: #ffd700; }
        .action-btn { height: 50px; font-size: 1rem; font-weight: bold; }
        
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        /* ========================================= */
        /* CSS SƠ ĐỒ BÀN (TABLE MAP)                 */
        /* ========================================= */
        .floor-plan { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 20px; padding: 20px; background: #e9ecef url('https://www.transparenttextures.com/patterns/cubes.png'); border-radius: 15px; border: 2px dashed #ccc; }
        .table-widget { width: 100px; height: 100px; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.15); position: relative; }
        .table-widget:hover { transform: scale(1.1); z-index: 10; }
        .table-empty { background: linear-gradient(145deg, #2ecc71, #27ae60); color: white; border: 4px solid #1e8449; }
        .table-occupied { background: linear-gradient(145deg, #e74c3c, #c0392b); color: white; border: 4px solid #922b21; animation: pulse-red 2s infinite; }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); } 70% { box-shadow: 0 0 0 15px rgba(231, 76, 60, 0); } 100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); } }
        .table-name { font-weight: 900; font-size: 1.2rem; margin-bottom: 2px; }
        .table-status { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; background: rgba(0,0,0,0.2); padding: 2px 8px; border-radius: 10px; }

        /* ========================================= */
        /* CSS BÀN PHÍM SỐ (NUMPAD)                  */
        /* ========================================= */
        .numpad-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; margin-top: 8px; }
        .btn-num { background: #444; color: white; border: 1px solid #555; border-radius: 6px; font-size: 1.2rem; font-weight: bold; padding: 8px 0; transition: 0.1s; cursor: pointer; }
        .btn-num:active { background: #8d5524; transform: scale(0.95); }
        .btn-num.clear { background: #dc3545; border-color: #bd2130; }
        .btn-num.del { background: #fd7e14; border-color: #d39e00; }
        .btn-num.double-zero { background: #555; }
        .btn-num.exact { grid-column: span 2; background: #198754; border-color: #146c43; }
    </style>
</head>
<body>

<div class="pos-container">
    <div class="left-panel">
        <div class="search-bar d-flex justify-content-between align-items-center">
            <div class="input-group w-75">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchInput" class="form-control border-start-0 bg-light" placeholder="Tìm tên món ăn, đồ uống...">
            </div>
            <a href="<?= URLROOT ?>/dashboard" class="btn btn-outline-secondary"><i class="fas fa-home"></i> Thoát</a>
        </div>
        <div class="category-tabs" id="categoryTabs"></div>
        <div class="product-grid" id="productGrid"></div>
    </div>

    <div class="right-panel">
        <div class="order-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-warning fw-bold"><i class="fas fa-receipt me-2"></i>ĐƠN HÀNG</h5>
                <button class="btn btn-warning fw-bold shadow-sm w-50" onclick="openMapModal()">
                    <i class="fas fa-map-marked-alt me-1"></i> SƠ ĐỒ BÀN
                </button>
            </div>
            <div class="mt-2 text-end">
                <small id="currentTableInfo" class="text-light opacity-75">Vui lòng chọn bàn để order</small>
            </div>
        </div>

        <div class="order-body" id="orderItems">
            <div class="text-center text-muted mt-5 opacity-50">
                <i class="fas fa-shopping-basket fa-4x mb-3"></i>
                <p>Chưa có món nào.</p>
            </div>
        </div>

        <div class="order-footer">
            <div class="row">
                <div class="col-6 pe-1">
                    <div class="total-line"><span class="text-light opacity-75">Tạm tính:</span><span id="subTotal">0 đ</span></div>
                    
                    <div class="total-line align-items-center mt-1">
                        <span class="text-light opacity-75"><i class="fas fa-tags me-1"></i>Giảm:</span>
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <input type="number" id="discountInput" class="form-control text-end bg-dark text-warning border-secondary px-1" value="0" min="0" onkeyup="updateTotals()" onchange="updateTotals()">
                            <select id="discountType" class="form-select bg-dark text-warning border-secondary px-1" onchange="updateTotals()">
                                <option value="amount">đ</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                    </div>

                    <div class="total-line mt-1"><span class="text-light opacity-75">VAT (10%):</span><span id="taxAmount">0 đ</span></div>
                    <hr class="border-secondary my-1">
                    <div class="total-line grand-total mt-1"><span>TỔNG:</span><span id="grandTotal">0 đ</span></div>

                    <div class="mt-2 p-2 rounded" style="background: rgba(255,255,255,0.05); border: 1px dashed #555;">
                        <div class="total-line align-items-center">
                            <span class="text-light opacity-75">Khách đưa:</span>
                            <input type="text" id="customerCash" class="form-control form-control-sm text-end fw-bold bg-dark text-white border-secondary w-50" placeholder="0" readonly>
                        </div>
                        <div class="total-line mt-1">
                            <span class="text-light opacity-75">Tiền thừa:</span>
                            <span id="changeDue" class="text-muted fw-bold">0 đ</span>
                        </div>
                        <select id="paymentMethod" class="form-select form-select-sm mt-1 bg-dark text-light border-secondary">
                            <option value="Tiền mặt">Tiền mặt</option>
                            <option value="Chuyển khoản (QR)">Chuyển khoản (QR)</option>
                            <option value="Thẻ ngân hàng">Thẻ ngân hàng</option>
                        </select>
                    </div>
                </div>

                <div class="col-6 ps-1">
                    <div class="numpad-grid">
                        <button type="button" class="btn-num" onclick="numpadInput('1')">1</button>
                        <button type="button" class="btn-num" onclick="numpadInput('2')">2</button>
                        <button type="button" class="btn-num" onclick="numpadInput('3')">3</button>
                        
                        <button type="button" class="btn-num" onclick="numpadInput('4')">4</button>
                        <button type="button" class="btn-num" onclick="numpadInput('5')">5</button>
                        <button type="button" class="btn-num" onclick="numpadInput('6')">6</button>
                        
                        <button type="button" class="btn-num" onclick="numpadInput('7')">7</button>
                        <button type="button" class="btn-num" onclick="numpadInput('8')">8</button>
                        <button type="button" class="btn-num" onclick="numpadInput('9')">9</button>
                        
                        <button type="button" class="btn-num clear" onclick="numpadClear()">C</button>
                        <button type="button" class="btn-num" onclick="numpadInput('0')">0</button>
                        <button type="button" class="btn-num del" onclick="numpadDel()"><i class="fas fa-backspace"></i></button>
                        
                        <button type="button" class="btn-num double-zero" onclick="numpadInput('000')">.000</button>
                        <button type="button" class="btn-num exact" onclick="numpadExact()"><i class="fas fa-check-circle me-1"></i> Vừa đủ</button>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-3">
                <div class="row g-2">
                    <div class="col-3">
                        <button onclick="cancelOrder()" class="btn btn-danger action-btn w-100 shadow-sm" title="Hủy đơn"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="col-3">
                        <button onclick="sendToKitchen()" class="btn btn-success action-btn w-100 shadow-sm" title="Báo bếp"><i class="fas fa-bell"></i></button>
                    </div>
                    <div class="col-6">
                        <button onclick="checkoutOrder()" class="btn btn-primary action-btn w-100 shadow-sm"><i class="fas fa-print me-1"></i> TÍNH TIỀN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tableMapModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-dark text-warning border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-th me-2"></i> Sơ đồ Không gian quán</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="floor-plan">
                    <?php foreach ($tables as $table): ?>
                        <div class="table-widget <?= $table['status'] === 'occupied' ? 'table-occupied' : 'table-empty' ?>" 
                             onclick="selectMapTable(<?= $table['id'] ?>, '<?= htmlspecialchars($table['table_name']) ?>', '<?= $table['status'] ?>')">
                            <span class="table-name"><?= htmlspecialchars($table['table_name']) ?></span>
                            <span class="table-status">
                                <?= $table['status'] === 'occupied' ? '<i class="fas fa-users"></i> Khách' : '<i class="fas fa-check"></i> Trống' ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let products = <?= json_encode($products) ?>;
let currentOrder = [];
let selectedTableId = null;
let selectedTableName = '';
let currentGrandTotal = 0;
let currentDiscountAmount = 0;
let mapModal;

window.onload = function() {
    renderCategories();
    renderProducts(products);
};

// ==========================================
// LOGIC BÀN PHÍM SỐ (NUMPAD) MỚI THÊM
// ==========================================
function numpadInput(numStr) {
    let input = document.getElementById('customerCash');
    let currentVal = input.value.replace(/\D/g, ''); // Xóa chữ, chỉ lấy số
    if (currentVal === '0') currentVal = ''; // Xóa số 0 thừa ở đầu
    let newVal = currentVal + numStr;
    input.value = Number(newVal).toLocaleString('vi-VN');
    calculateChange();
}

function numpadClear() {
    document.getElementById('customerCash').value = '';
    calculateChange();
}

function numpadDel() {
    let input = document.getElementById('customerCash');
    let currentVal = input.value.replace(/\D/g, '');
    if (currentVal.length > 0) {
        let newVal = currentVal.slice(0, -1); // Xóa ký tự cuối
        input.value = newVal ? Number(newVal).toLocaleString('vi-VN') : '';
    }
    calculateChange();
}

function numpadExact() {
    // Tự động điền tiền khách đưa = Tổng bill
    document.getElementById('customerCash').value = currentGrandTotal.toLocaleString('vi-VN');
    calculateChange();
}

// ==========================================
// CÁC HÀM XỬ LÝ SẢN PHẨM & TÍNH TOÁN (Giữ nguyên)
// ==========================================
function renderCategories() {
    const tabsContainer = document.getElementById('categoryTabs');
    const categories = [...new Set(products.map(p => p.category_name))];
    let html = `<div class="category-tab active" onclick="filterCategory('all', this)">Tất cả</div>`;
    categories.forEach(cat => { html += `<div class="category-tab" onclick="filterCategory('${cat}', this)">${cat}</div>`; });
    tabsContainer.innerHTML = html;
}

function renderProducts(filteredProducts) {
    const grid = document.getElementById('productGrid');
    if (filteredProducts.length === 0) { grid.innerHTML = `<div class="col-12 text-center text-muted py-5 w-100">Không tìm thấy sản phẩm.</div>`; return; }
    let html = '';
    filteredProducts.forEach(p => {
        html += `
        <div class="product-card" onclick="addProductToOrder(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.price}, '${p.image}')">
            <img src="<?= ASSET_IMG ?>${p.image}" alt="${p.name}" class="w-100" onerror="this.src='<?= ASSET_IMG ?>default.jpg'">
            <div class="p-2">
                <div class="fw-bold text-dark text-truncate" style="font-size:0.9rem">${p.name}</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <span class="text-primary fw-bold" style="font-size:0.9rem">${Number(p.price).toLocaleString('vi-VN')} đ</span>
                </div>
            </div>
        </div>`;
    });
    grid.innerHTML = html;
}

function filterCategory(category, element) {
    document.querySelectorAll('.category-tab').forEach(tab => tab.classList.remove('active'));
    element.classList.add('active');
    renderProducts(category === 'all' ? products : products.filter(p => p.category_name === category));
}

document.getElementById('searchInput').addEventListener('input', function() {
    const term = this.value.toLowerCase().trim();
    renderProducts(products.filter(p => p.name.toLowerCase().includes(term)));
});

function openMapModal() {
    if (!mapModal) { mapModal = new bootstrap.Modal(document.getElementById('tableMapModal')); }
    mapModal.show();
}

function selectMapTable(id, name, status) {
    selectedTableId = id;
    selectedTableName = name;
    document.getElementById('currentTableInfo').innerHTML = `Bàn <strong>${selectedTableName}</strong> - ${status === 'occupied' ? '<span class="text-danger fw-bold"><i class="fas fa-users me-1"></i>Có khách</span>' : '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Trống</span>'}`;
    if(mapModal) mapModal.hide();
    
    document.getElementById('discountInput').value = '0';
    document.getElementById('customerCash').value = '';
    currentOrder = []; 
    
    if (status === 'occupied') {
        fetch('<?= URLROOT ?>/order/get_active_order/' + selectedTableId)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.details) {
                data.details.forEach(item => {
                    currentOrder.push({ product_id: parseInt(item.product_id), name: item.name, price: parseFloat(item.unit_price), quantity: parseInt(item.quantity) });
                });
            }
            renderOrderItems();
        }).catch(() => renderOrderItems());
    } else {
        renderOrderItems();
    }
}

function addProductToOrder(id, name, price) {
    if (!selectedTableId) return alert('⚠️ Vui lòng chọn bàn trước khi order!');
    const existing = currentOrder.find(item => item.product_id === id);
    if (existing) existing.quantity++;
    else currentOrder.push({ product_id: id, name: name, price: parseFloat(price), quantity: 1 });
    renderOrderItems();
}

function renderOrderItems() {
    const container = document.getElementById('orderItems');
    if (currentOrder.length === 0) {
        container.innerHTML = `<div class="text-center text-muted mt-5 opacity-50"><i class="fas fa-shopping-basket fa-4x mb-3"></i><p>Chưa có món nào.</p></div>`;
        updateTotals();
        return;
    }
    
    let html = '';
    currentOrder.forEach((item, index) => {
        html += `
        <div class="order-item shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div class="pe-2">
                    <div class="fw-bold text-light" style="font-size:0.95rem">${item.name}</div>
                    <small class="text-muted">${item.price.toLocaleString('vi-VN')} đ</small>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-warning mb-2" style="font-size:0.95rem">${(item.price * item.quantity).toLocaleString('vi-VN')} đ</div>
                    <div class="d-flex align-items-center justify-content-end bg-dark rounded p-1">
                        <button onclick="currentOrder[${index}].quantity = Math.max(1, currentOrder[${index}].quantity - 1); renderOrderItems()" class="btn btn-sm btn-dark text-white border-0 py-0"><i class="fas fa-minus"></i></button>
                        <span class="mx-2 fw-bold" style="width: 15px; text-align: center;">${item.quantity}</span>
                        <button onclick="currentOrder[${index}].quantity++; renderOrderItems()" class="btn btn-sm btn-dark text-white border-0 py-0"><i class="fas fa-plus"></i></button>
                        <button onclick="currentOrder.splice(${index}, 1); renderOrderItems()" class="btn btn-sm btn-danger ms-2 border-0 py-0"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>`;
    });
    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
    updateTotals();
}

function updateTotals() {
    let subTotal = currentOrder.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discountVal = parseFloat(document.getElementById('discountInput').value) || 0;
    let discountType = document.getElementById('discountType').value;
    currentDiscountAmount = discountType === 'percent' ? subTotal * (discountVal / 100) : discountVal;
    
    let afterDiscount = subTotal - currentDiscountAmount;
    if (afterDiscount < 0) afterDiscount = 0;
    
    let tax = afterDiscount * 0.1;
    currentGrandTotal = afterDiscount + tax;
    
    document.getElementById('subTotal').textContent = subTotal.toLocaleString('vi-VN') + ' đ';
    document.getElementById('taxAmount').textContent = tax.toLocaleString('vi-VN') + ' đ';
    document.getElementById('grandTotal').textContent = currentGrandTotal.toLocaleString('vi-VN') + ' đ';
    
    calculateChange();
}

function calculateChange() {
    let cashStr = document.getElementById('customerCash').value.replace(/\D/g, '');
    let cash = parseFloat(cashStr) || 0;
    let change = cash - currentGrandTotal;
    
    let changeEl = document.getElementById('changeDue');
    if (cash === 0) {
        changeEl.textContent = '0 đ';
        changeEl.className = 'text-muted fw-bold';
    } else if (change < 0) {
        changeEl.textContent = 'Thiếu ' + Math.abs(change).toLocaleString('vi-VN') + ' đ';
        changeEl.className = 'text-danger fw-bold';
    } else {
        changeEl.textContent = change.toLocaleString('vi-VN') + ' đ';
        changeEl.className = 'text-success fw-bold';
    }
}

function sendToKitchen() { if (currentOrder.length > 0) submitOrder('processing'); }
function cancelOrder() { if (confirm('Hủy bỏ toàn bộ đơn hàng?')) { currentOrder = []; renderOrderItems(); document.getElementById('customerCash').value = ''; } }

function checkoutOrder() {
    if (!selectedTableId || currentOrder.length === 0) return alert('Chưa có đơn hàng để thanh toán!');
    
    let cashStr = document.getElementById('customerCash').value.replace(/\D/g, '');
    let cash = parseFloat(cashStr) || 0;
    if (cash > 0 && cash < currentGrandTotal) {
        return alert('Tiền khách đưa không đủ để thanh toán!');
    }

    if (confirm('Xác nhận TÍNH TIỀN và IN HÓA ĐƠN?')) {
        submitOrder('completed', true); 
    }
}

function submitOrder(status, isCheckout = false) {
    const items = currentOrder.map(item => ({ 
        product_id: item.product_id, quantity: item.quantity, unit_price: item.price 
    }));
    
    fetch('<?= URLROOT ?>/order/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            table_id: selectedTableId, staff_id: <?= $_SESSION['user_id'] ?? 1 ?>, status: status, items: items 
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { 
            if (isCheckout) printInvoice(); else location.reload();
        } else { 
            alert('❌ Lỗi: ' + data.message); 
        }
    })
    .catch(() => alert('Lỗi kết nối đến máy chủ!'));
}

function printInvoice() {
    let subTotal = currentOrder.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let tax = (subTotal - currentDiscountAmount) * 0.1;
    let timeNow = new Date().toLocaleString('vi-VN');
    let cashStr = document.getElementById('customerCash').value.replace(/\D/g, '');
    let cash = parseFloat(cashStr) || 0;
    let change = cash - currentGrandTotal;
    let paymentMethod = document.getElementById('paymentMethod').value;

    let billContent = `
        <div style="width: 100%; max-width: 320px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; color: #000;">
            <h2 style="text-align: center; margin-bottom: 5px;">COFFEE SHOP</h2>
            <p style="text-align: center; margin: 0; font-size: 13px;">ĐC: Khu phố 1, Dĩ An, Bình Dương</p>
            <hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <h3 style="text-align: center; margin: 10px 0;">PHIẾU THANH TOÁN</h3>
            <p style="margin: 0; font-size: 14px;">Bàn: <b>${selectedTableName}</b></p>
            <p style="margin: 0 0 10px 0; font-size: 14px;">Ngày: ${timeNow}</p>
            <hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <table style="width: 100%; font-size: 14px; text-align: left; border-collapse: collapse;">
                <tr><th style="padding-bottom: 5px; border-bottom: 1px solid #000;">Món</th><th style="padding-bottom: 5px; border-bottom: 1px solid #000; text-align: center;">SL</th><th style="padding-bottom: 5px; border-bottom: 1px solid #000; text-align: right;">Tiền</th></tr>`;
                
    currentOrder.forEach(item => {
        billContent += `<tr><td style="padding: 8px 0;">${item.name}</td><td style="padding: 8px 0; text-align: center;">${item.quantity}</td><td style="padding: 8px 0; text-align: right;">${(item.price * item.quantity).toLocaleString('vi-VN')}</td></tr>`;
    });
    
    billContent += `</table><hr style="border-top: 1px dashed #000; margin: 10px 0;"><table style="width: 100%; font-size: 14px;">
                <tr><td style="padding: 3px 0;">Tạm tính:</td><td style="text-align: right; padding: 3px 0;">${subTotal.toLocaleString('vi-VN')} đ</td></tr>`;
                
    if (currentDiscountAmount > 0) {
        billContent += `<tr><td style="padding: 3px 0;">Giảm giá:</td><td style="text-align: right; padding: 3px 0;">-${currentDiscountAmount.toLocaleString('vi-VN')} đ</td></tr>`;
    }

    billContent += `<tr><td style="padding: 3px 0;">Thuế VAT (10%):</td><td style="text-align: right; padding: 3px 0;">${tax.toLocaleString('vi-VN')} đ</td></tr>
                <tr><td style="font-size: 18px; font-weight: bold; padding-top: 10px;">TỔNG CỘNG:</td><td style="font-size: 18px; font-weight: bold; text-align: right; padding-top: 10px;">${currentGrandTotal.toLocaleString('vi-VN')} đ</td></tr>
            </table><hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <table style="width: 100%; font-size: 14px;">
                <tr><td style="padding: 3px 0;">Hình thức TT:</td><td style="text-align: right; padding: 3px 0;">${paymentMethod}</td></tr>
                <tr><td style="padding: 3px 0;">Khách đưa:</td><td style="text-align: right; padding: 3px 0;">${cash > 0 ? cash.toLocaleString('vi-VN') : currentGrandTotal.toLocaleString('vi-VN')} đ</td></tr>
                <tr><td style="padding: 3px 0;">Tiền thừa:</td><td style="text-align: right; padding: 3px 0;">${change > 0 ? change.toLocaleString('vi-VN') : '0'} đ</td></tr>
            </table>
            <p style="text-align: center; font-size: 13px; margin: 15px 0 0 0;">Xin cảm ơn & Hẹn gặp lại!</p>
        </div>`;
    
    let printWindow = window.open('', '_blank', 'width=400,height=600');
    printWindow.document.write('<html><head><title>In Hóa Đơn</title></head><body>' + billContent + '</body></html>');
    printWindow.document.close();
    
    setTimeout(() => { printWindow.print(); printWindow.close(); location.reload(); }, 500);
}
</script>
</body>
</html>