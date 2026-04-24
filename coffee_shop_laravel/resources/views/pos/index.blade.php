<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ thống POS | {{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { --primary-coffee: #6f4e37; --secondary-coffee: #935d2d; --dark-panel: #1c1917; --bg-light: #fdf8f5; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); height: 100vh; overflow: hidden; margin: 0; color: #444; }
        
        .pos-left { height: 100vh; display: flex; flex-direction: column; background: #fff; padding: 20px; border-right: 1px solid #e7e5e4; }
        .pos-right { height: 100vh; background: var(--dark-panel); color: white; display: flex; flex-direction: column; box-shadow: -10px 0 30px rgba(0,0,0,0.1); }

        .category-scroll { display: flex; gap: 12px; overflow-x: auto; padding: 10px 0; scrollbar-width: none; }
        .btn-cat { background: #f5f5f4; border: none; color: #78716c; font-weight: 700; padding: 12px 25px; border-radius: 15px; white-space: nowrap; transition: 0.3s; font-size: 0.85rem; }
        .btn-cat.active { background: var(--primary-coffee); color: #fff; box-shadow: 0 8px 15px rgba(111,78,55,0.2); }

        .product-grid { flex-grow: 1; overflow-y: auto; margin-top: 15px; padding-right: 5px; }
        .product-card { border: 1px solid #e7e5e4; border-radius: 20px; cursor: pointer; transition: 0.3s; background: white; height: 100%; position: relative; overflow: hidden; }
        .product-img { width: 100%; height: 130px; object-fit: cover; }

        .cart-items { flex-grow: 1; overflow-y: auto; padding: 20px; }
        .cart-item { display: flex; align-items: center; border-bottom: 1px solid #292524; padding-bottom: 15px; margin-bottom: 15px; }
        .split-checkbox { display: none; margin-right: 15px; width: 22px; height: 22px; accent-color: #f59e0b; }

        .table-item { height: 110px; border: 2px solid #e7e5e4; border-radius: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: white; position: relative; transition: 0.3s; }
        .table-item.available { border-color: #10b981; color: #10b981; }
        .table-item.occupied { border-color: #ef4444; background: #fee2e2; color: #ef4444; }
        .table-item.pending { border-color: #f59e0b; background: #fffbeb; color: #f59e0b; animation: pulse-yellow 2s infinite; }
        .badge-notify { position: absolute; top: 10px; right: 10px; width: 12px; height: 12px; background: #ef4444; border-radius: 50%; border: 2px solid white; display: none; }
        .pending .badge-notify { display: block; }

        .calc-area { background: #12100e; padding: 20px; border-top: 1px solid #292524; }
        .pay-btn { width: 100%; border: 1px solid #44403c; background: #292524; color: #a8a29e; padding: 10px; border-radius: 10px; font-weight: 700; font-size: 0.75rem; }
        .pay-btn.active { background: var(--primary-coffee); color: white; border-color: var(--primary-coffee); }

        .keypad { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; padding: 15px 20px; background: var(--dark-panel); }
        .key-btn { background: #292524; color: #fff; border: 1px solid #44403c; border-radius: 12px; padding: 15px 0; font-weight: 700; font-size: 1.2rem; transition: 0.2s; }
        .key-btn:active { background: #44403c; transform: scale(0.9); }

        @keyframes pulse-yellow { 0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); } 70% { box-shadow: 0 0 0 15px rgba(245, 158, 11, 0); } 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); } }
    </style>
</head>
<body>

<audio id="orderNotificationSound" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-lg-7 col-xl-8 pos-left">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="flex-grow-1 position-relative">
                    <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="productSearch" class="form-control ps-5 border-0 bg-light rounded-4 py-3 shadow-sm" placeholder="Tìm tên món ăn...">
                </div>
                <button id="audio-status" class="btn btn-dark rounded-4 px-3" onclick="enableAudio()" title="Bật âm thanh báo đơn">
                    <i class="fa fa-volume-mute text-warning"></i>
                </button>
                <button class="btn btn-warning rounded-4 p-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#tableModal">
                    <i class="fa fa-map-location-dot me-1"></i> SƠ ĐỒ BÀN
                </button>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-danger rounded-4 p-3"><i class="fa fa-power-off"></i></button></form>
            </div>

            <div class="category-scroll mb-2">
                <button class="btn-cat active" onclick="filterCategory('all', this)">Tất cả</button>
                @foreach($categories as $cat)
                    <button class="btn-cat" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button>
                @endforeach
            </div>

            <div class="product-grid">
                <div class="row g-4" id="productList">
                    @foreach($products as $pro)
                    <div class="col-6 col-md-4 col-xl-3 product-item" data-category="{{ $pro->category_id }}" data-name="{{ strtolower($pro->name) }}">
                        <div class="product-card shadow-sm" onclick="addToCart({{ $pro->id }}, '{{ $pro->name }}', {{ $pro->price }})">
                            <img src="{{ asset('img/'.$pro->image) }}" class="product-img" onerror="this.src='https://placehold.co/400x300?text=Coffee'">
                            <div class="p-3 text-center">
                                <div class="fw-bold small text-truncate text-uppercase text-dark">{{ $pro->name }}</div>
                                <div class="text-coffee fw-800 fs-5">{{ number_format($pro->price) }}đ</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-xl-4 pos-right animate__animated animate__fadeInRight">
            <div class="p-4 d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-25">
                <div>
                    <h5 class="m-0 text-warning fw-800 uppercase italic" id="selectedTableName">Chưa chọn bàn</h5>
                    <small class="text-secondary" id="orderInfoLabel">Vui lòng chọn bàn</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-warning btn-sm rounded-pill dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">Thao tác</button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openTransferModal()"><i class="fa fa-exchange me-2 text-info"></i> Chuyển bàn</a></li>
                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openMergeModal()"><i class="fa fa-object-group me-2 text-success"></i> Gộp bàn</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-warning" href="javascript:void(0)" onclick="toggleSplitMode()"><i class="fa fa-scissors me-2"></i> Tách bàn / món</a></li>
                    </ul>
                </div>
            </div>

            <div class="cart-items" id="cartContent">
                <div class="text-center mt-5 opacity-25"><i class="fa-solid fa-mug-hot fa-4x mb-3"></i><p>Giỏ hàng trống</p></div>
            </div>

            <div id="splitPanel" class="p-3 bg-warning text-dark fw-bold d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Đã chọn <span id="splitCount">0</span> món</span>
                    <button class="btn btn-dark btn-sm fw-bold" onclick="executeSplit()">XÁC NHẬN TÁCH</button>
                </div>
            </div>

            <div class="px-4 pb-3" id="kitchenArea" style="display: none;">
                <button class="btn btn-warning w-100 py-3 fw-800 rounded-4 shadow" onclick="sendToKitchen()">
                    <i class="fa-solid fa-fire-burner me-2"></i> GỬI XUỐNG BẾP
                </button>
            </div>

            <div class="calc-area">
                <div class="d-flex justify-content-between align-items-center text-warning mb-3">
                    <span class="fw-bold">TỔNG CỘNG:</span>
                    <h2 class="fw-800 m-0" id="totalAmount">0đ</h2>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4"><button class="pay-btn active" id="pay-cash" onclick="setPayment('cash', this)">TIỀN MẶT</button></div>
                    <div class="col-4"><button class="pay-btn" id="pay-card" onclick="setPayment('card', this)">THẺ/POS</button></div>
                    <div class="col-4"><button class="pay-btn" id="pay-banking" onclick="setPayment('banking', this)">BANKING</button></div>
                </div>

                <input type="text" id="cashInput" class="form-control bg-dark border-secondary text-warning text-end fw-800 fs-2 py-3 rounded-4 mb-2" placeholder="Khách đưa..." readonly>
                <div class="d-flex justify-content-between text-white-50 small mb-2">
                    <span>TIỀN THỪA:</span>
                    <span id="changeAmount" class="text-success fw-bold fs-5">0đ</span>
                </div>
            </div>

            <div class="keypad">
                <button class="key-btn" onclick="pressKey('1')">1</button>
                <button class="key-btn" onclick="pressKey('2')">2</button>
                <button class="key-btn" onclick="pressKey('3')">3</button>
                <button class="key-btn bg-danger border-danger" onclick="clearKey()">C</button>
                <button class="key-btn" onclick="pressKey('4')">4</button>
                <button class="key-btn" onclick="pressKey('5')">5</button>
                <button class="key-btn" onclick="pressKey('6')">6</button>
                <button class="key-btn" onclick="delKey()"><i class="fa fa-backspace"></i></button>
                <button class="key-btn" onclick="pressKey('7')">7</button>
                <button class="key-btn" onclick="pressKey('8')">8</button>
                <button class="key-btn" onclick="pressKey('9')">9</button>
                <button class="key-btn" onclick="pressKey('0')">0</button>
                <button class="key-btn" onclick="pressKey('000')" style="grid-column: span 2;">.000</button>
                <button class="key-btn bg-success border-success fw-bold" onclick="quickCash()" style="grid-column: span 2;">VỪA ĐỦ</button>
            </div>

            <div class="p-4">
                <button class="btn btn-primary w-100 py-4 fw-800 fs-5 rounded-4 shadow-lg border-0" id="btnCheckout" onclick="checkout()" style="background: linear-gradient(to right, #6f4e37, #935d2d)">
                    <i class="fa-solid fa-print me-2"></i> HOÀN TẤT & IN BILL
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title fw-800 uppercase italic">Sơ đồ bàn phục vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-5 bg-light"><div class="row g-4" id="tableGrid">
                @foreach($tables as $table)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="table-item {{ $table->status }}" id="table-{{ $table->id }}" onclick="selectOrderTable('{{ $table->id }}', '{{ $table->name }}', '{{ $table->status }}')">
                        <div class="badge-notify"></div><i class="fa fa-couch fa-2x mb-2"></i>
                        <span class="fw-bold">{{ $table->name }}</span>
                        <small class="text-uppercase" style="font-size: 0.65rem;">{{ $table->status }}</small>
                    </div>
                </div>
                @endforeach
            </div></div>
        </div>
    </div>
</div>

<div class="modal fade" id="targetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-warning"><h5 class="modal-title fw-bold" id="targetTitle">Chọn bàn đích</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4"><div class="row g-2" id="targetList"></div></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let cart = [];
    let selectedTableId = null;
    let currentOrderId = null;
    let isSplitMode = false;
    let totalValue = 0;
    let cashStr = "";
    let paymentMethod = 'cash';
    let lastPendingCount = 0;

    // 1. REAL-TIME POLLING & ÂM THANH
    function refreshTables() {
        $.get("{{ route('tables.fetch_status') }}", function(tables) {
            let pendingCount = tables.filter(t => t.status === 'pending').length;
            if (pendingCount > lastPendingCount) playOrderSound();
            lastPendingCount = pendingCount;
            tables.forEach(t => { $('#table-' + t.id).removeClass('available occupied pending').addClass(t.status); });
        });
    }
    setInterval(refreshTables, 5000);

    function playOrderSound() {
        let sound = document.getElementById('orderNotificationSound');
        sound.currentTime = 0;
        sound.play().catch(e => console.log("Click để bật tiếng"));
    }

    function enableAudio() {
        playOrderSound();
        $('#audio-status').html('<i class="fa fa-volume-up"></i>').removeClass('btn-dark').addClass('btn-success text-white');
    }

    // 2. CHỌN BÀN & FIX LỖI ID
    async function selectOrderTable(id, name, status) {
        selectedTableId = id; cart = []; currentOrderId = null; isSplitMode = false;
        $('#selectedTableName').text(name); $('#kitchenArea, #splitPanel').hide();
        clearKey();
        
        if (status !== 'available') {
            const res = await $.get(`/pos/table-order/${id}`);
            if (res.success) {
                currentOrderId = res.order_id;
                // FIX LỖI 500: Lấy i.id thay vì i.product_id
                res.details.forEach(i => {
                    cart.push({ 
                        id: i.id, // ID sản phẩm từ server
                        detail_id: i.detail_id, 
                        name: i.name, 
                        price: i.price, 
                        qty: i.qty 
                    });
                });
                if (res.status === 'pending') $('#kitchenArea').show();
            }
        }
        renderCart();
        bootstrap.Modal.getInstance(document.getElementById('tableModal')).hide();
    }

    function renderCart() {
        let html = ''; totalValue = 0;
        cart.forEach((item, idx) => {
            totalValue += item.price * item.qty;
            html += `
                <div class="cart-item animate__animated animate__fadeIn">
                    <input type="checkbox" class="split-checkbox" value="${item.detail_id}" onchange="updateSplitCount()" ${isSplitMode ? 'style="display:block"' : ''}>
                    <div style="flex-grow:1">
                        <div class="fw-bold small text-white text-uppercase">${item.name}</div>
                        <small class="text-secondary">${item.price.toLocaleString()}đ</small>
                    </div>
                    <div class="d-flex align-items-center bg-dark rounded-pill px-2 py-1">
                        <button class="btn btn-sm text-warning p-0 fw-bold" onclick="updateQty(${idx}, -1)" style="width:20px">-</button>
                        <span class="mx-3 small fw-800 text-white">${item.qty}</span>
                        <button class="btn btn-sm text-warning p-0 fw-bold" onclick="updateQty(${idx}, 1)" style="width:20px">+</button>
                    </div>
                    <div class="text-warning fw-800 small text-end ms-3" style="min-width:70px">${(item.price * item.qty).toLocaleString()}đ</div>
                </div>`;
        });
        $('#cartContent').html(cart.length ? html : '<div class="text-center mt-5 opacity-25"><i class="fa-solid fa-mug-hot fa-4x mb-3"></i><p>Trống</p></div>');
        $('#totalAmount').text(totalValue.toLocaleString() + 'đ');
        updateChange();
    }

    function addToCart(id, name, price) {
        if (!selectedTableId) return alert("Vui lòng chọn bàn trước!");
        let item = cart.find(i => i.id === id);
        if (item) item.qty++; else cart.push({ id, name, price, qty: 1 });
        renderCart();
    }

    function updateQty(idx, d) { cart[idx].qty += d; if (cart[idx].qty <= 0) cart.splice(idx, 1); renderCart(); }

    // 3. THANH TOÁN (CÓ BẮT LỖI CHI TIẾT)
    async function checkout() {
        if (!selectedTableId || !cart.length) return alert("Giỏ hàng rỗng!");
        if (paymentMethod === 'cash' && (parseInt(cashStr) || 0) < totalValue) return alert("Tiền khách đưa không đủ!");
        
        let $btn = $('#btnCheckout');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...');

        $.ajax({
            url: "{{ route('pos.checkout') }}",
            method: "POST",
            data: { 
                table_id: selectedTableId, order_id: currentOrderId, cart: cart, 
                payment_method: paymentMethod, total_amount: totalValue,
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(res) {
                if (res.success) {
                    window.open(`/pos/print-receipt/${res.order_id}`, '_blank');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert("Lỗi: " + res.message);
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-print me-2"></i> HOÀN TẤT & IN BILL');
                }
            },
            error: function(xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "Lỗi Server không xác định";
                alert("❌ THANH TOÁN THẤT BẠI: " + msg);
                $btn.prop('disabled', false).html('<i class="fa-solid fa-print me-2"></i> HOÀN TẤT & IN BILL');
            }
        });
    }

    // 4. BÀN PHÍM & TÍNH TOÁN
    function setPayment(m, b) { $('.pay-btn').removeClass('active'); $(b).addClass('active'); paymentMethod = m; if(m !== 'cash') quickCash(); else clearKey(); }
    function pressKey(v) { cashStr += v; updateCashUI(); }
    function delKey() { cashStr = cashStr.slice(0, -1); updateCashUI(); }
    function clearKey() { cashStr = ""; updateCashUI(); }
    function quickCash() { cashStr = totalValue.toString(); updateCashUI(); }
    function updateCashUI() { $('#cashInput').val(cashStr ? parseInt(cashStr).toLocaleString() + 'đ' : ""); updateChange(); }
    function updateChange() { let c = (parseInt(cashStr) || 0) - totalValue; $('#changeAmount').text((c > 0 ? c.toLocaleString() : 0) + 'đ'); }

    // 5. NGHIỆP VỤ BÀN
    function openTransferModal() { if(!currentOrderId) return alert("Bàn trống!"); $('#targetTitle').text("CHUYỂN SANG BÀN TRỐNG"); loadTargets('available', 'transfer'); }
    function openMergeModal() { if(!currentOrderId) return alert("Bàn trống!"); $('#targetTitle').text("GỘP VÀO BÀN KHÁC"); loadTargets('occupied', 'merge'); }
    function loadTargets(status, type, extra = null) {
        $.get("{{ route('tables.fetch_status') }}", function(tables) {
            let html = '';
            tables.filter(t => t.status === status && t.id != selectedTableId).forEach(t => {
                html += `<div class="col-4"><button class="btn btn-outline-dark w-100 py-3 fw-bold" onclick="${type === 'split' ? `finalizeSplit(${t.id}, [${extra}])` : `confirmManage('${type}', ${t.id})`}">${t.name}</button></div>`;
            });
            $('#targetList').html(html || '<p class="text-center w-100">Không có bàn phù hợp</p>');
            new bootstrap.Modal('#targetModal').show();
        });
    }
    function confirmManage(type, tid) { $.post(`/table-manage/${type}`, { from_table_id: selectedTableId, to_table_id: tid, _token: $('meta[name="csrf-token"]').attr('content') }, res => { if(res.success) location.reload(); }); }
    function toggleSplitMode() { if(!currentOrderId) return; isSplitMode = !isSplitMode; $('.split-checkbox').toggle(isSplitMode); $('#splitPanel').toggleClass('d-none', !isSplitMode); }
    function updateSplitCount() { $('#splitCount').text($('.split-checkbox:checked').length); }
    function executeSplit() {
        let ids = []; $('.split-checkbox:checked').each(function() { ids.push($(this).val()); });
        if(!ids.length) return alert("Vui lòng chọn món!");
        $('#targetTitle').text("TÁCH SANG BÀN TRỐNG"); loadTargets('available', 'split', ids);
    }
    function finalizeSplit(tid, ids) { $.post("{{ route('table.split') }}", { order_detail_ids: ids, to_table_id: tid, _token: $('meta[name="csrf-token"]').attr('content') }, res => { if(res.success) location.reload(); }); }
    
    async function sendToKitchen() {
        if (!currentOrderId) return;
        $('#kitchenArea button').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> ĐANG GỬI...');
        $.post("{{ route('pos.send_kitchen') }}", { order_id: currentOrderId, _token: $('meta[name="csrf-token"]').attr('content') }, res => { if(res.success) location.reload(); });
    }
</script>
</body>
</html>