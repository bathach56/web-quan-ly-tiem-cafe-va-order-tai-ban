<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ thống POS | {{ $shop_setting->shop_name ?? 'Premium Coffee' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { --primary: #d97706; --primary-dark: #b45309; --dark-panel: #1e1e2d; --bg-light: #f8f9fa; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); height: 100vh; overflow: hidden; margin: 0; }
        
        /* Layout */
        .pos-left { height: 100vh; display: flex; flex-direction: column; background: #fff; padding: 20px; border-right: 1px solid #e2e8f0; }
        .pos-right { height: 100vh; background: var(--dark-panel); color: white; display: flex; flex-direction: column; }

        /* Menu Items */
        .category-scroll { display: flex; gap: 10px; overflow-x: auto; padding: 10px 0; scrollbar-width: none; }
        .btn-cat { background: #f1f5f9; border: none; color: #475569; font-weight: 600; padding: 10px 24px; border-radius: 50px; white-space: nowrap; transition: 0.3s; }
        .btn-cat.active { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(217,119,6,0.3); }

        .product-grid { flex-grow: 1; overflow-y: auto; margin-top: 15px; }
        .product-card { border: 1px solid #e2e8f0; border-radius: 20px; cursor: pointer; transition: 0.3s; background: white; height: 100%; }
        .product-card:hover { border-color: var(--primary); transform: translateY(-3px); }
        .product-img { width: 100%; height: 120px; object-fit: cover; border-radius: 20px 20px 0 0; }

        /* Cart Styles */
        .cart-items { flex-grow: 1; overflow-y: auto; padding: 20px; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #3f3f5a; padding-bottom: 12px; margin-bottom: 12px; }

        /* Virtual Keypad */
        .calc-area { background: #151521; padding: 20px; border-top: 1px solid #2b2b40; }
        .keypad { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding: 10px 20px; background: #1e1e2d; }
        .key-btn { background: #2b2b40; color: #fff; border: 1px solid #3f3f5a; border-radius: 10px; padding: 12px 0; font-weight: 700; }
        .key-btn:active { background: var(--primary); }

        /* Table Status */
        .table-item { height: 100px; border: 2px solid #e2e8f0; border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: white; transition: 0.3s; }
        .table-item.empty { border-color: #10b981; color: #10b981; }
        .table-item.occupied { border-color: #ef4444; background: #fee2e2; color: #ef4444; }
        .table-item.waiting { border-color: #f59e0b; background: #fffbeb; color: #f59e0b; animation: pulse-yellow 1.5s infinite; }
        
        @keyframes pulse-yellow {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-lg-7 col-xl-8 pos-left">
            <div class="d-flex gap-2 mb-3">
                <div class="input-group flex-grow-1 shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" id="productSearch" class="form-control border-0" placeholder="Tìm món nhanh...">
                </div>
                <button class="btn btn-light border" onclick="location.reload()"><i class="fa fa-sync"></i></button>
                <a href="{{ route('logout') }}" class="btn btn-danger fw-bold"><i class="fa fa-power-off"></i></a>
            </div>

            <div class="category-scroll">
                <button class="btn-cat active" onclick="filterCategory('all', this)">Tất cả</button>
                @foreach($categories as $cat)
                    <button class="btn-cat" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button>
                @endforeach
            </div>

            <div class="product-grid">
                <div class="row g-3" id="productList">
                    @foreach($products as $pro)
                    <div class="col-6 col-md-4 col-xl-3 product-item" data-category="{{ $pro->category_id }}" data-name="{{ strtolower($pro->name) }}">
                        <div class="product-card shadow-sm" onclick="addToCart({{ $pro->id }}, '{{ $pro->name }}', {{ $pro->price }})">
                            <img src="{{ asset('img/'.$pro->image) }}" class="product-img" onerror="this.src='https://placehold.co/200x150?text=Coffee'">
                            <div class="p-2 text-center">
                                <div class="fw-bold small mb-1 text-truncate">{{ $pro->name }}</div>
                                <div class="text-primary fw-bold">{{ number_format($pro->price) }}đ</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-xl-4 pos-right animate__animated animate__fadeInRight">
            <div class="header-right d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-warning fw-800"><i class="fa-solid fa-receipt me-2"></i> ĐƠN HÀNG</h5>
                <button class="btn btn-warning fw-bold px-4 rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#tableModal">
                    <i class="fa-solid fa-couch me-1"></i> <span id="selectedTableName">Chọn bàn</span>
                </button>
            </div>

            <div class="cart-items" id="cartContent">
                <div class="text-center mt-5 opacity-25">
                    <i class="fa-solid fa-cart-shopping fa-4x mb-3"></i>
                    <p>Chưa có món nào</p>
                </div>
            </div>

            <div class="px-3 pb-2" id="kitchenArea" style="display: none;">
                <button class="btn btn-warning w-100 py-3 fw-800 shadow" onclick="sendToKitchen()">
                    <i class="fa-solid fa-fire-burner me-2"></i> GỬI XUỐNG BẾP (PHA CHẾ)
                </button>
            </div>

            <div class="calc-area">
                <div class="d-flex justify-content-between align-items-center text-warning mb-2">
                    <span class="fw-bold">TỔNG CỘNG:</span>
                    <h3 class="fw-800 m-0" id="totalAmount">0đ</h3>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-4"><button class="pay-btn active" onclick="setPayment('cash', this)">Tiền mặt</button></div>
                    <div class="col-4"><button class="pay-btn" onclick="setPayment('card', this)">Quẹt thẻ</button></div>
                    <div class="col-4"><button class="pay-btn" onclick="setPayment('banking', this)">Banking</button></div>
                </div>
                <input type="text" id="cashInput" class="form-control bg-transparent border-warning text-warning text-end fw-bold fs-3 mb-2" placeholder="Khách đưa..." readonly>
                <div class="d-flex justify-content-between text-white-50 small">
                    <span>TIỀN THỪA:</span>
                    <span id="changeAmount" class="text-success fs-5 fw-bold">0đ</span>
                </div>
            </div>

            <div class="keypad">
                <button class="key-btn" onclick="pressKey('1')">1</button>
                <button class="key-btn" onclick="pressKey('2')">2</button>
                <button class="key-btn" onclick="pressKey('3')">3</button>
                <button class="key-btn bg-danger" onclick="clearKey()">C</button>
                <button class="key-btn" onclick="pressKey('4')">4</button>
                <button class="key-btn" onclick="pressKey('5')">5</button>
                <button class="key-btn" onclick="pressKey('6')">6</button>
                <button class="key-btn" onclick="pressKey('0')">0</button>
                <button class="key-btn" onclick="pressKey('7')">7</button>
                <button class="key-btn" onclick="pressKey('8')">8</button>
                <button class="key-btn" onclick="pressKey('9')">9</button>
                <button class="key-btn bg-warning text-dark" onclick="delKey()"><i class="fa-solid fa-backspace"></i></button>
                <button class="key-btn" onclick="pressKey('000')" style="grid-column: span 2;">.000</button>
                <button class="key-btn bg-success" onclick="quickCash()" style="grid-column: span 2;">VỪA ĐỦ</button>
            </div>

            <div class="p-3">
                <button class="btn btn-primary w-100 py-3 fw-800 fs-5 shadow-lg" id="btnCheckout" onclick="checkout()">
                    <i class="fa-solid fa-print me-2"></i> THANH TOÁN & IN BILL
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">SƠ ĐỒ BÀN PHỤC VỤ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    @foreach($tables as $table)
                    <div class="col-4 col-md-3">
                        <div class="table-item {{ $table->status }}" onclick="handleTableClick('{{ $table->id }}', '{{ $table->name }}', '{{ $table->status }}', this)">
                            <i class="fa fa-couch fa-2x mb-2"></i>
                            <span class="fw-bold">{{ $table->name }}</span>
                            <small>{{ $table->area }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = [];
    let selectedTableId = null;
    let currentOrderId = null;
    let totalValue = 0;
    let cashStr = "";
    let paymentMethod = 'cash';

    // 1. TÌM KIẾM & LỌC
    document.getElementById('productSearch').addEventListener('input', e => {
        let kw = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(i => i.style.display = i.dataset.name.includes(kw) ? 'block' : 'none');
    });

    function filterCategory(id, btn) {
        document.querySelectorAll('.btn-cat').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.product-item').forEach(i => i.style.display = (id === 'all' || i.dataset.category === id) ? 'block' : 'none');
    }

    // 2. CLICK BÀN - NẠP ĐƠN QR TỰ ĐỘNG
    async function handleTableClick(id, name, status, el) {
        selectedTableId = id;
        currentOrderId = null;
        cart = [];
        document.getElementById('selectedTableName').innerText = name;
        document.getElementById('kitchenArea').style.display = 'none';

        if (status === 'occupied' || status === 'waiting') {
            await loadOrderFromTable(id);
        }

        renderCart();
        bootstrap.Modal.getInstance(document.getElementById('tableModal')).hide();
    }

    async function loadOrderFromTable(tableId) {
        try {
            const response = await fetch(`/pos/table-order/${tableId}`);
            const res = await response.json();
            if (res.success) {
                currentOrderId = res.order_id;
                res.details.forEach(item => {
                    cart.push({ id: item.id, name: item.name, price: item.price, qty: item.qty });
                });
                if (res.status === 'pending') document.getElementById('kitchenArea').style.display = 'block';
            }
        } catch (e) { console.error(e); }
    }

    // 3. GỬI XUỐNG BẾP
    async function sendToKitchen() {
        if (!currentOrderId) return;
        const response = await fetch("/pos/send-kitchen", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ order_id: currentOrderId })
        });
        const res = await response.json();
        if (res.success) { alert("Đã gửi đơn xuống bếp!"); location.reload(); }
    }

    // 4. GIỎ HÀNG
    function addToCart(id, name, price) {
        if (!selectedTableId) return alert("Vui lòng chọn bàn!");
        let item = cart.find(i => i.id === id);
        if (item) item.qty++; else cart.push({ id, name, price, qty: 1 });
        renderCart();
    }

    function renderCart() {
        let html = ''; totalValue = 0;
        cart.forEach((item, idx) => {
            totalValue += item.price * item.qty;
            html += `
                <div class="cart-item animate__animated animate__fadeIn">
                    <div style="width:55%"><div class="fw-bold small text-white">${item.name}</div><small class="text-secondary">${item.price.toLocaleString()}đ</small></div>
                    <div class="d-flex align-items-center bg-secondary bg-opacity-25 rounded-pill px-2">
                        <button class="btn btn-sm text-warning p-0 fw-bold" onclick="updateQty(${idx}, -1)">-</button>
                        <span class="mx-2 small fw-bold">${item.qty}</span>
                        <button class="btn btn-sm text-warning p-0 fw-bold" onclick="updateQty(${idx}, 1)">+</button>
                    </div>
                    <div class="text-warning fw-bold small text-end" style="width:25%">${(item.price * item.qty).toLocaleString()}đ</div>
                </div>`;
        });
        document.getElementById('cartContent').innerHTML = cart.length ? html : '<div class="text-center mt-5 opacity-25"><i class="fa-solid fa-cart-shopping fa-4x mb-3"></i><p>Trống</p></div>';
        document.getElementById('totalAmount').innerText = totalValue.toLocaleString() + 'đ';
        updateChange();
    }

    function updateQty(idx, delta) {
        cart[idx].qty += delta;
        if (cart[idx].qty <= 0) cart.splice(idx, 1);
        renderCart();
    }

    // 5. THANH TOÁN & TỰ ĐỘNG IN BILL
    async function checkout() {
        if (!selectedTableId || !cart.length) return alert('Dữ liệu trống!');
        
        const btn = document.getElementById('btnCheckout');
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('pos.checkout') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ table_id: selectedTableId, order_id: currentOrderId, cart: cart, payment_method: paymentMethod, total_amount: totalValue })
            });
            const res = await response.json();
            if (res.success) {
                // Mở tab in bill mới
                const printWindow = window.open(`/pos/print-receipt/${res.order_id}`, '_blank');
                setTimeout(() => location.reload(), 1000);
            } else { alert(res.message); btn.disabled = false; }
        } catch (e) { alert("Lỗi kết nối!"); btn.disabled = false; }
    }

    // Keypad Logic
    function setPayment(m, b) { document.querySelectorAll('.pay-btn').forEach(x => x.classList.remove('active')); b.classList.add('active'); paymentMethod = m; }
    function pressKey(v) { cashStr += v; updateCashUI(); }
    function delKey() { cashStr = cashStr.slice(0, -1); updateCashUI(); }
    function clearKey() { cashStr = ""; updateCashUI(); }
    function quickCash() { cashStr = totalValue.toString(); updateCashUI(); }
    function updateCashUI() { 
        let v = parseInt(cashStr) || 0; 
        document.getElementById('cashInput').value = v ? v.toLocaleString() + 'đ' : ""; 
        updateChange(); 
    }
    function updateChange() {
        let c = (parseInt(cashStr) || 0) - totalValue;
        document.getElementById('changeAmount').innerText = (c > 0 ? c.toLocaleString() : 0) + 'đ';
    }
</script>
</body>
</html>