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
        :root {
            --primary: #8d5524;
        }
        * { font-family: 'Segoe UI', system-ui, sans-serif; }
        
        body {
            height: 100vh;
            overflow: hidden;
            background: #f8f1e3;
        }
        
        .pos-container {
            height: 100vh;
            display: flex;
        }
        
        /* ================= CỘT TRÁI - SẢN PHẨM ================= */
        .left-panel {
            flex: 0 0 65%;
            display: flex;
            flex-direction: column;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 10;
        }
        
        .search-bar { padding: 15px 20px; background: #fff; border-bottom: 1px solid #eee; }
        
        .category-tabs {
            padding: 10px 20px;
            background: #fff;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .category-tab {
            padding: 8px 20px;
            border-radius: 50px;
            background: #f1f1f1;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .category-tab.active { background: var(--primary); color: white; }
        
        .product-grid {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
            align-content: start;
        }
        
        .product-card {
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            background: #fff;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(141, 85, 36, 0.2);
            border-color: var(--primary);
        }
        
        .product-card img { height: 120px; object-fit: cover; }
        
        /* ================= CỘT PHẢI - ORDER ================= */
        .right-panel {
            flex: 0 0 35%;
            background: #2c2c2c;
            color: white;
            display: flex;
            flex-direction: column;
        }
        
        .order-header {
            background: #1f1f1f;
            padding: 15px 20px;
            border-bottom: 1px solid #444;
        }
        
        .order-body { flex: 1; overflow-y: auto; padding: 20px; }
        
        .order-item {
            background: #3a3a3a;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary);
        }
        
        .order-footer {
            background: #1f1f1f;
            padding: 20px;
            border-top: 1px solid #444;
        }
        
        .total-line { display: flex; justify-content: space-between; font-size: 1.1rem; margin-bottom: 8px; }
        .grand-total { font-size: 1.5rem; font-weight: bold; color: #ffd700; }
        .action-btn { height: 55px; font-size: 1.1rem; font-weight: bold; }
        .table-select { background: #3a3a3a; color: white; border: 1px solid #555; }
        .table-select:focus { background: #4a4a4a; color: white; box-shadow: none; border-color: var(--primary); }
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

        <div class="category-tabs" id="categoryTabs">
            </div>

        <div class="product-grid" id="productGrid">
            </div>
    </div>

    <div class="right-panel">
        <div class="order-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-warning"><i class="fas fa-receipt me-2"></i>ĐƠN HÀNG</h4>
                
                <select id="tableSelect" class="form-select table-select w-50" onchange="selectTable()">
                    <option value="">-- Chọn bàn --</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?= $table['id'] ?>" 
                                data-name="<?= htmlspecialchars($table['table_name']) ?>"
                                data-status="<?= $table['status'] ?>">
                            <?= htmlspecialchars($table['table_name']) ?>
                            (<?= $table['status'] === 'occupied' ? 'Đang có khách' : 'Trống' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-2 text-end">
                <small id="currentTableInfo" class="text-light opacity-75">Vui lòng chọn bàn để order</small>
            </div>
        </div>

        <div class="order-body" id="orderItems">
            <div class="text-center text-muted mt-5 opacity-50">
                <i class="fas fa-shopping-basket fa-4x mb-3"></i>
                <p>Chưa có món nào.<br>Chọn bàn và thêm sản phẩm từ bên trái.</p>
            </div>
        </div>

        <div class="order-footer">
            <div class="total-line">
                <span class="text-light opacity-75">Tạm tính:</span>
                <span id="subTotal">0 đ</span>
            </div>
            <div class="total-line">
                <span class="text-light opacity-75">Thuế VAT (10%):</span>
                <span id="taxAmount">0 đ</span>
            </div>
            <hr class="border-secondary my-2">
            <div class="total-line grand-total mt-2">
                <span>TỔNG CỘNG:</span>
                <span id="grandTotal">0 đ</span>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button onclick="sendToKitchen()" class="btn btn-success action-btn shadow-sm">
                    <i class="fas fa-concierge-bell me-2"></i> GỬI XUỐNG BẾP
                </button>
                <div class="row g-2">
                    <div class="col-4">
                        <button onclick="cancelOrder()" class="btn btn-danger action-btn w-100 shadow-sm">
                            <i class="fas fa-trash-alt"></i> HỦY
                        </button>
                    </div>
                    <div class="col-4">
                        <button onclick="saveOrder()" class="btn btn-warning text-dark action-btn w-100 shadow-sm">
                            <i class="fas fa-save"></i> LƯU
                        </button>
                    </div>
                    <div class="col-4">
                        <button onclick="checkoutOrder()" class="btn btn-primary action-btn w-100 shadow-sm">
                            <i class="fas fa-print"></i> TÍNH TIỀN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ==================== KHỞI TẠO DỮ LIỆU ====================
let products = <?= json_encode($products) ?>;
let currentOrder = [];
let selectedTableId = null;
let selectedTableName = '';

window.onload = function() {
    renderCategories();
    renderProducts(products);
    document.getElementById('searchInput').focus();
};

// ==================== RENDER GIAO DIỆN TRÁI ====================
function renderCategories() {
    const tabsContainer = document.getElementById('categoryTabs');
    const categories = [...new Set(products.map(p => p.category_name))];
    
    let html = `<div class="category-tab active" onclick="filterCategory('all')">Tất cả</div>`;
    categories.forEach(cat => {
        html += `<div class="category-tab" onclick="filterCategory('${cat}')">${cat}</div>`;
    });
    tabsContainer.innerHTML = html;
}

function renderProducts(filteredProducts) {
    const grid = document.getElementById('productGrid');
    if (filteredProducts.length === 0) {
        grid.innerHTML = `<div class="col-12 text-center text-muted py-5 w-100">Không tìm thấy sản phẩm phù hợp.</div>`;
        return;
    }

    let html = '';
    filteredProducts.forEach(product => {
        html += `
        <div class="product-card" onclick="addProductToOrder(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.image}')">
            <img src="<?= ASSET_IMG ?>${product.image}" alt="${product.name}" class="w-100" onerror="this.src='<?= ASSET_IMG ?>default.jpg'">
            <div class="p-2">
                <div class="fw-bold text-dark text-truncate" style="font-size:0.95rem" title="${product.name}">${product.name}</div>
                <div class="text-muted" style="font-size:0.8rem">${product.category_name}</div>
                <div class="d-flex justify-content-between align-items-end mt-1">
                    <span class="text-primary fw-bold">${Number(product.price).toLocaleString('vi-VN')} đ</span>
                    <i class="fas fa-plus-circle text-success fs-5"></i>
                </div>
            </div>
        </div>`;
    });
    grid.innerHTML = html;
}

function filterCategory(category) {
    document.querySelectorAll('.category-tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    let filtered = category === 'all' ? products : products.filter(p => p.category_name === category);
    renderProducts(filtered);
}

document.getElementById('searchInput').addEventListener('input', function() {
    const term = this.value.toLowerCase().trim();
    const filtered = products.filter(p => p.name.toLowerCase().includes(term));
    renderProducts(filtered);
});

// ==================== XỬ LÝ CHỌN BÀN & ĐƠN HÀNG ====================
// [ĐÃ FIX] Đồng bộ dữ liệu bàn đang có khách từ DB
function selectTable() {
    const select = document.getElementById('tableSelect');
    if(select.selectedIndex === 0) {
        selectedTableId = null;
        document.getElementById('currentTableInfo').innerHTML = 'Vui lòng chọn bàn để order';
        currentOrder = [];
        renderOrderItems();
        return;
    }
    
    const option = select.options[select.selectedIndex];
    selectedTableId = parseInt(select.value);
    selectedTableName = option.dataset.name || '';
    const status = option.dataset.status;
    
    document.getElementById('currentTableInfo').innerHTML = `Bàn <strong>${selectedTableName}</strong> - ${status === 'occupied' ? '<span class="text-danger fw-bold"><i class="fas fa-users me-1"></i>Đang phục vụ</span>' : '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Bàn trống</span>'}`;
    
    currentOrder = []; 
    
    if (status === 'occupied') {
        // Tải dữ liệu khách vừa quét QR đặt món
        fetch('<?= URLROOT ?>/order/get_active_order/' + selectedTableId)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.details) {
                data.details.forEach(item => {
                    currentOrder.push({
                        product_id: parseInt(item.product_id),
                        name: item.name,
                        price: parseFloat(item.unit_price),
                        image: item.image,
                        quantity: parseInt(item.quantity)
                    });
                });
            }
            renderOrderItems();
        }).catch(err => renderOrderItems());
    } else {
        renderOrderItems();
    }
}

function addProductToOrder(id, name, price, image) {
    if (!selectedTableId) {
        alert('⚠️ Vui lòng chọn bàn ở cột bên phải trước khi chọn món!');
        document.getElementById('tableSelect').focus();
        return;
    }
    
    const existing = currentOrder.find(item => item.product_id === id);
    if (existing) existing.quantity++;
    else currentOrder.push({ product_id: id, name: name, price: parseFloat(price), image: image, quantity: 1 });
    
    renderOrderItems();
}

function renderOrderItems() {
    const container = document.getElementById('orderItems');
    if (currentOrder.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted mt-5 opacity-50">
                <i class="fas fa-shopping-basket fa-4x mb-3"></i>
                <p>Chưa có món nào.<br>Thêm sản phẩm từ danh sách bên trái.</p>
            </div>`;
        updateTotals();
        return;
    }
    
    let html = '';
    currentOrder.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        html += `
        <div class="order-item shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div class="pe-2">
                    <div class="fw-bold text-light">${item.name}</div>
                    <small class="text-muted">${item.price.toLocaleString('vi-VN')} đ</small>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-warning mb-2">${subtotal.toLocaleString('vi-VN')} đ</div>
                    <div class="d-flex align-items-center justify-content-end bg-dark rounded p-1">
                        <button onclick="changeQuantity(${index}, -1)" class="btn btn-sm btn-dark text-white border-0"><i class="fas fa-minus"></i></button>
                        <span class="mx-2 fw-bold" style="width: 20px; text-align: center;">${item.quantity}</span>
                        <button onclick="changeQuantity(${index}, 1)" class="btn btn-sm btn-dark text-white border-0"><i class="fas fa-plus"></i></button>
                        <button onclick="removeItem(${index})" class="btn btn-sm btn-danger ms-2 border-0"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>
        </div>`;
    });
    container.innerHTML = html;
    
    // Auto scroll down to bottom
    container.scrollTop = container.scrollHeight;
    updateTotals();
}

function changeQuantity(index, change) {
    currentOrder[index].quantity += change;
    if (currentOrder[index].quantity < 1) currentOrder[index].quantity = 1;
    renderOrderItems();
}

function removeItem(index) {
    currentOrder.splice(index, 1);
    renderOrderItems();
}

function updateTotals() {
    let subTotal = currentOrder.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let tax = subTotal * 0.1;
    let grandTotal = subTotal + tax;
    
    document.getElementById('subTotal').textContent = subTotal.toLocaleString('vi-VN') + ' đ';
    document.getElementById('taxAmount').textContent = tax.toLocaleString('vi-VN') + ' đ';
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('vi-VN') + ' đ';
}

// ==================== XỬ LÝ LƯU & IN BILL ====================
function sendToKitchen() {
    if (!selectedTableId || currentOrder.length === 0) return alert('Chưa có món nào để gửi bếp!');
    submitOrder('processing');
}

function saveOrder() {
    if (!selectedTableId || currentOrder.length === 0) return alert('Chưa có món nào để lưu!');
    submitOrder('pending');
}

function cancelOrder() {
    if (confirm('⚠️ Bạn có chắc chắn muốn HỦY xóa trắng toàn bộ đơn hàng của bàn này?')) {
        currentOrder = [];
        renderOrderItems();
    }
}

// [ĐÃ FIX] Chức năng in Bill và hoàn tất thanh toán
function checkoutOrder() {
    if (!selectedTableId || currentOrder.length === 0) return alert('Chưa có đơn hàng để thanh toán!');
    if (confirm('Xác nhận TÍNH TIỀN và IN HÓA ĐƠN cho bàn này?')) {
        printInvoice();             // Gọi hàm sinh giao diện máy in nhiệt
        submitOrder('completed');   // Gửi cờ 'completed' xuống PHP để lưu và giải phóng bàn
    }
}

function printInvoice() {
    let subTotal = currentOrder.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let tax = subTotal * 0.1;
    let grandTotal = subTotal + tax;
    let timeNow = new Date().toLocaleString('vi-VN');

    let billContent = `
        <div style="width: 100%; max-width: 320px; margin: 0 auto; font-family: 'Courier New', Courier, monospace; color: #000;">
            <h2 style="text-align: center; margin-bottom: 5px;">COFFEE SHOP MVC</h2>
            <p style="text-align: center; margin: 0; font-size: 13px;">ĐC: Khu phố 1, Dĩ An, Bình Dương</p>
            <p style="text-align: center; margin: 0; font-size: 13px;">SĐT: 0988.xxx.xxx</p>
            <hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <h3 style="text-align: center; margin: 10px 0;">PHIẾU THANH TOÁN</h3>
            <p style="margin: 0; font-size: 14px;">Bàn: <b>${selectedTableName}</b></p>
            <p style="margin: 0 0 10px 0; font-size: 14px;">Ngày in: ${timeNow}</p>
            <hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <table style="width: 100%; font-size: 14px; text-align: left; border-collapse: collapse;">
                <tr>
                    <th style="padding-bottom: 5px; border-bottom: 1px solid #000;">Món</th>
                    <th style="padding-bottom: 5px; border-bottom: 1px solid #000; text-align: center;">SL</th>
                    <th style="padding-bottom: 5px; border-bottom: 1px solid #000; text-align: right;">Tiền</th>
                </tr>`;
                
    currentOrder.forEach(item => {
        let rowTotal = item.price * item.quantity;
        billContent += `
                <tr>
                    <td style="padding: 8px 0;">${item.name}</td>
                    <td style="padding: 8px 0; text-align: center;">${item.quantity}</td>
                    <td style="padding: 8px 0; text-align: right;">${rowTotal.toLocaleString('vi-VN')}</td>
                </tr>`;
    });
    
    billContent += `
            </table>
            <hr style="border-top: 1px dashed #000; margin: 10px 0;">
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 3px 0;">Tạm tính:</td>
                    <td style="text-align: right; padding: 3px 0;">${subTotal.toLocaleString('vi-VN')} đ</td>
                </tr>
                <tr>
                    <td style="padding: 3px 0;">Thuế VAT (10%):</td>
                    <td style="text-align: right; padding: 3px 0;">${tax.toLocaleString('vi-VN')} đ</td>
                </tr>
                <tr>
                    <td style="font-size: 18px; font-weight: bold; padding-top: 15px;">TỔNG CỘNG:</td>
                    <td style="font-size: 18px; font-weight: bold; text-align: right; padding-top: 15px;">${grandTotal.toLocaleString('vi-VN')} đ</td>
                </tr>
            </table>
            <hr style="border-top: 1px dashed #000; margin: 15px 0 10px 0;">
            <p style="text-align: center; font-size: 13px; margin: 5px 0;">Xin cảm ơn quý khách & Hẹn gặp lại!</p>
            <p style="text-align: center; font-size: 11px; margin: 5px 0; font-style: italic;">Powered by PHP MVC</p>
        </div>
    `;
    
    let printWindow = window.open('', '_blank', 'width=400,height=600');
    printWindow.document.write('<html><head><title>In Hóa Đơn - ' + selectedTableName + '</title></head><body>');
    printWindow.document.write(billContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 300);
}

// [ĐÃ FIX] Thêm biến status đẩy xuống Backend
function submitOrder(status) {
    const items = currentOrder.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity,
        unit_price: item.price
    }));
    
    fetch('<?= URLROOT ?>/order/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            table_id: selectedTableId,
            staff_id: <?= $_SESSION['user_id'] ?? 1 ?>, // Truyền ID nhân viên thật (nếu có session)
            status: status, // Trạng thái order (pending, processing, completed)
            items: items
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let msg = status === 'completed' ? 'đã thanh toán thành công' : status === 'processing' ? 'đã gửi bếp' : 'đã được lưu';
            alert(`✅ Đơn hàng #${data.order_id} ${msg}!`);
            location.reload(); // Tải lại trang để reset trạng thái các bàn
        } else {
            alert('❌ Lỗi: ' + data.message);
        }
    })
    .catch(() => alert('Lỗi kết nối đến máy chủ!'));
}
</script>
</body>
</html>