<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS | {{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --amber: #f59e0b; --amber-dark: #b45309; --panel-bg: #1a1816; --panel-mid: #242220;
            --panel-light: #2e2b28; --panel-border: #3d3935; --text-muted: #78716c;
            --surface: #ffffff; --surface-2: #f9f6f3; --border-light: #e7e2dd; --danger: #ef4444;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { height: 100vh; overflow: hidden; font-family: 'Inter', sans-serif; background: var(--surface-2); margin: 0; }
        .pos-wrapper { display: grid; grid-template-columns: 1fr 420px; height: 100vh; overflow: hidden; }

        /* --- LEFT PANEL --- */
        .pos-left { display: flex; flex-direction: column; background: var(--surface); border-right: 1px solid var(--border-light); overflow: hidden; }
        .pos-topbar { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--border-light); background: white; flex-shrink: 0; }
        .logo-icon { width: 44px; height: 44px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        
        .search-wrap { flex: 1; position: relative; }
        .search-wrap input { width: 100%; height: 44px; border: 1.5px solid var(--border-light); border-radius: 12px; padding-left: 40px; outline: none; }
        .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

        .btn-takeaway { height: 44px; padding: 0 15px; background: #27272a; color: white; border: 1.5px solid #3f3f46; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-takeaway:hover { background: #3f3f46; border-color: var(--amber); }

        .btn-table { height: 44px; padding: 0 15px; background: var(--amber); color: white; border: none; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; position: relative; }
        .order-badge-main { position: absolute; top: -5px; right: -5px; background: var(--danger); color: white; border: 2px solid white; font-size: 0.7rem; padding: 2px 6px; border-radius: 50px; }

        .cat-row { display: flex; gap: 8px; padding: 12px 20px; overflow-x: auto; scrollbar-width: none; border-bottom: 1px solid var(--border-light); flex-shrink: 0; }
        .cat-btn { height: 38px; padding: 0 16px; border: 1.5px solid var(--border-light); border-radius: 10px; background: white; white-space: nowrap; cursor: pointer; font-weight: 600; font-size: 0.8rem; }
        .cat-btn.active { background: var(--amber); border-color: var(--amber); color: white; }

        .product-area { flex: 1; overflow-y: auto; padding: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .product-card { background: white; border: 1.5px solid var(--border-light); border-radius: 18px; overflow: hidden; cursor: pointer; transition: 0.2s; }
        .product-card:hover { transform: translateY(-4px); border-color: var(--amber); }
        .product-img-wrap { width: 100%; aspect-ratio: 4/3; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid var(--border-light); }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }

        /* --- RIGHT PANEL --- */
        .pos-right { display: flex; flex-direction: column; background: var(--panel-bg); color: white; overflow-y: auto !important; height: 100vh; scrollbar-width: thin; }
        .cart-header { padding: 18px 20px; border-bottom: 1px solid var(--panel-border); flex-shrink: 0; }
        .cart-list { flex: 1; overflow-y: auto; padding: 10px 20px; min-height: 150px; }
        .cart-item { display: flex; align-items: center; gap: 10px; padding: 12px 0; border-bottom: 1px solid var(--panel-border); }
        .qty-ctrl { display: flex; align-items: center; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 10px; }
        .qty-btn { width: 30px; height: 30px; background: none; border: none; color: var(--amber); font-weight: 800; cursor: pointer; }

        .promo-strip { padding: 12px 20px; display: flex; gap: 10px; background: var(--panel-mid); flex-shrink: 0; }
        .totals-area { padding: 15px 20px; background: var(--panel-mid); border-top: 1px solid var(--panel-border); flex-shrink: 0; }
        .total-main .val { font-size: 1.8rem; font-weight: 900; color: var(--amber); }

        .pay-methods { display: flex; gap: 8px; margin-bottom: 12px; }
        .pay-method-btn { flex: 1; height: 42px; background: var(--panel-light); border: 1.5px solid var(--panel-border); border-radius: 10px; color: #a8a29e; font-weight: 700; font-size: 0.75rem; cursor: pointer; }
        .pay-method-btn.active { border-color: var(--amber); color: var(--amber); background: rgba(245,158,11,0.1); }

        .cash-display { width: 100%; height: 50px; background: var(--panel-light); border: 1.5px solid var(--amber); border-radius: 12px; color: var(--amber); font-size: 1.5rem; font-weight: 900; text-align: right; padding: 0 15px; outline: none; margin-bottom: 10px; }

        .keypad { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; flex-shrink: 0; padding-bottom: 20px; }
        .key { height: 46px; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 10px; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .key.k-clear { background: #7f1d1d; color: #fca5a5; }
        .key.k-exact { background: rgba(34,197,94,0.15); color: #4ade80; }

        .btn-checkout { width: 100%; height: 60px; background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dark) 100%); border: none; border-radius: 16px; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }

        .table-status-badge { position: absolute; top: -8px; right: -8px; background: var(--danger); color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; border: 3px solid white; box-shadow: 0 3px 8px rgba(239, 68, 68, 0.4); z-index: 10; }
        
        .pos-toast { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: #1c1917; color: white; padding: 12px 24px; border-radius: 20px; z-index: 10000; opacity: 0; transition: 0.3s; border: 1px solid var(--amber); pointer-events: none; }
        .pos-toast.show { opacity: 1; }

        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }
        .table-new-order { animation: pulse-red 2s infinite; border-color: var(--danger) !important; color: var(--danger) !important; }
    </style>
</head>
<body>

<audio id="notifSound" src="{{ asset('audio/notify.mp3') }}" preload="auto"></audio>

<div class="pos-wrapper">
    <!-- CỘT TRÁI -->
    <div class="pos-left">
        <div class="pos-topbar">
            <div class="logo-icon">
                @if(isset($shop_setting->logo)) <img src="{{ asset('img/' . $shop_setting->logo) }}"> @else <i class="fa-solid fa-mug-hot text-white"></i> @endif
            </div>
            <div class="brand-info d-none d-xl-block">
                <div class="brand-name fw-900">{{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</div>
                <small class="text-muted" style="font-size: 0.6rem;">Nhóm 3 - POS System</small>
            </div>
            <div class="search-wrap ms-2">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="productSearch" placeholder="Tìm tên món..." onkeyup="filterProducts()">
            </div>
            <div class="d-flex gap-2 ms-3">
                <button class="btn-takeaway" onclick="setTakeaway()">
                    <i class="fa-solid fa-bag-shopping"></i> MANG VỀ
                </button>
                <button class="btn-table" data-bs-toggle="modal" data-bs-target="#tableModal" id="btnMainTable">
                    <i class="fa-solid fa-couch"></i> <span>BÀN</span>
                    <span id="mainBadge" class="order-badge-main d-none">0</span>
                </button>
                <a href="#" class="btn-exit" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-power-off"></i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>

        <div class="cat-row">
            <button class="cat-btn active" onclick="filterCategory('all', this)">Tất cả</button>
            @foreach($categories as $cat) <button class="cat-btn" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button> @endforeach
        </div>

        <div class="product-area">
            <div class="product-grid" id="productList">
                @foreach($products as $pro)
                <div class="product-item" data-category="{{ $pro->category_id }}" data-name="{{ strtolower($pro->name) }}">
                    <div class="product-card" onclick="addToCart({{ $pro->id }}, '{{ addslashes($pro->name) }}', {{ $pro->price }})">
                        <div class="product-img-wrap"><img src="{{ asset('img/'.$pro->image) }}" onerror="this.src='https://placehold.co/400x300?text=Coffee'"></div>
                        <div class="p-3 text-center">
                            <div class="fw-bold small text-uppercase mb-1 text-truncate">{{ $pro->name }}</div>
                            <div style="color: var(--amber-dark); font-weight: 800;">{{ number_format($pro->price) }}đ</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CỘT PHẢI -->
    <div class="pos-right">
        <div class="cart-header d-flex justify-content-between align-items-center">
            <div><div class="text-warning fw-900" id="selectedTableName">CHỌN BÀN HOẶC MANG VỀ...</div><small class="opacity-50">NV: {{ Auth::user()->name }}</small></div>
            <button class="qty-btn" style="color:var(--danger)" onclick="confirmReset()"><i class="fa-solid fa-trash-can"></i></button>
        </div>

        <div class="cart-list" id="cartContent"><div class="text-center mt-5 opacity-25"><p>Giỏ hàng trống</p></div></div>

        <div class="promo-strip">
            <div style="flex:1"><small class="text-white-50 d-block mb-1" style="font-size:0.6rem">GIẢM (%)</small><input type="number" id="manual_discount" class="key w-100" style="background:var(--panel-light); border:1px solid var(--panel-border); height:38px; color:white; text-align:center;" placeholder="0" onchange="updateTotal()"></div>
            <div style="flex:1"><small class="text-white-50 d-block mb-1" style="font-size:0.6rem">VOUCHER</small><button class="key w-100" style="background:var(--panel-light); border:1px solid var(--panel-border); height:38px; color:var(--amber); font-size:0.7rem;" data-bs-toggle="modal" data-bs-target="#voucherModal"><span id="selectedVoucherLabel" class="text-truncate px-2">Chọn mã</span></button><input type="hidden" id="selected_voucher_code"></div>
        </div>

        <div class="totals-area">
            <div class="d-flex justify-content-between small opacity-75 mb-1"><span>Tạm tính</span> <span id="subtotalVal">0đ</span></div>
            <div class="d-flex justify-content-between small text-danger mb-2"><span>Giảm giá</span> <span id="discountVal">-0đ</span></div>
            
            <!-- Ghi chú đơn hàng -->
            <div class="mb-3">
                <small class="text-white-50 d-block mb-1" style="font-size: 0.7rem;">GHI CHÚ (Ít đá, ít đường...)</small>
                <textarea id="order_note" class="form-control bg-dark text-white border-secondary" rows="2" style="font-size: 0.8rem; border-radius: 10px; border-color: var(--panel-border)"></textarea>
            </div>

            <div class="total-main d-flex justify-content-between align-items-center mb-3"><span class="fw-bold">TỔNG CỘNG</span><span class="val" id="totalAmount">0đ</span></div>

            <div class="pay-methods">
                <button class="pay-method-btn active" onclick="setPayment('cash', this)">TIỀN MẶT</button>
                <button class="pay-method-btn" onclick="setPayment('card', this)">THẺ/POS</button>
                <button class="pay-method-btn" onclick="setPayment('banking', this)">BANKING</button>
            </div>

            <input type="text" id="cashInput" class="cash-display" placeholder="0" readonly>

            <div class="keypad">
                <button class="key" onclick="pressKey('1')">1</button><button class="key" onclick="pressKey('2')">2</button><button class="key" onclick="pressKey('3')">3</button><button class="key k-clear" onclick="clearKey()">C</button>
                <button class="key" onclick="pressKey('4')">4</button><button class="key" onclick="pressKey('5')">5</button><button class="key" onclick="pressKey('6')">6</button><button class="key" onclick="delKey()"><i class="fa-solid fa-delete-left"></i></button>
                <button class="key" onclick="pressKey('7')">7</button><button class="key" onclick="pressKey('8')">8</button><button class="key" onclick="pressKey('9')">9</button><button class="key" onclick="pressKey('0')">0</button>
                <button class="key" style="grid-column: span 2;" onclick="pressKey('000')">.000</button><button class="key k-exact" style="grid-column: span 2;" onclick="quickCash()">VỪA ĐỦ</button>
            </div>

            <button class="btn-checkout" id="btnCheckout" onclick="checkout()">XÁC NHẬN THANH TOÁN</button>
        </div>
    </div>
</div>

<!-- MODAL BÀN -->
<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 25px; overflow: hidden;">
            <div class="modal-header bg-dark text-white p-4 border-0"><h5 class="fw-800 m-0 text-uppercase">Sơ đồ bàn phục vụ</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    @foreach($tables as $table)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="table-box p-4 text-center border-2 border rounded-4 bg-white position-relative {{ $table->status === 'occupied' ? 'border-danger text-danger' : 'border-success text-success' }}" style="cursor:pointer" id="table-{{ $table->id }}" onclick="selectOrderTable('{{ $table->id }}', '{{ $table->name }}')">
                            <div class="table-status-badge {{ $table->status === 'occupied' ? '' : 'd-none' }}" id="badge-{{ $table->id }}"><i class="fa-solid fa-bell animate__animated animate__swing animate__infinite"></i></div>
                            <i class="fa-solid fa-mug-hot fa-2x mb-2"></i><div class="fw-bold">{{ $table->name }}</div><small class="text-uppercase status-label" style="font-size:0.6rem">{{ $table->status === 'occupied' ? 'Có khách' : 'Trống' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VOUCHER -->
<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white" style="border-radius: 20px;">
            <div class="modal-header border-secondary p-4"><h5 class="fw-800 m-0 text-warning">VOUCHER KHẢ DỤNG</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                @forelse($vouchers ?? [] as $v)
                <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center" style="cursor:pointer" onclick="applyVoucher('{{ $v->code }}', {{ $v->discount_value ?? 0 }}, '{{ $v->type }}', {{ $v->min_order_value ?? 0 }})">
                    <div style="flex:1"><div class="fw-bold text-warning fs-5">{{ $v->code }}</div><div class="small fw-bold">{{ $v->name }}</div></div>
                    <div class="badge bg-danger fs-6 px-3 py-2">-{{ $v->type == 'percentage' ? ($v->discount_value ?? 0).'%' : number_format($v->discount_value ?? 0).'đ' }}</div>
                </div>
                @empty <div class="p-5 text-center opacity-25"><p>Trống</p></div> @endforelse
            </div>
        </div>
    </div>
</div>

<div class="pos-toast" id="posToast"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let cart = []; let selectedTableId = null; let selectedOrderId = null;
    let subtotal = 0, totalValue = 0, cashStr = "", paymentMethod = 'cash';
    let voucherData = { code: null, value: 0, type: 'fixed' };

    $(document).on('show.bs.modal', '.modal', function () { $(this).appendTo('body'); });

    function showToast(msg) { $('#posToast').text(msg).addClass('show'); setTimeout(() => $('#posToast').removeClass('show'), 3000); }
    function filterProducts() { let kw = $('#productSearch').val().toLowerCase(); $('.product-item').each(function() { $(this).toggle($(this).data('name').includes(kw)); }); }
    function filterCategory(catId, btn) { $('.cat-btn').removeClass('active'); $(btn).addClass('active'); $('.product-item').each(function() { $(this).toggle(catId === 'all' || $(this).data('category') == catId); }); }

    function setTakeaway() {
        selectedTableId = 'takeaway'; selectedOrderId = null; cart = []; 
        $('#order_note').val('');
        renderCart();
        $('#selectedTableName').text('KHÁCH MANG VỀ').addClass('text-info text-uppercase').removeClass('text-warning');
        $('#topbarTableLabel').text('MANG VỀ');
        showToast("🔔 Chế độ: MANG VỀ");
    }

    function selectOrderTable(id, name) {
        selectedTableId = id; $('#selectedTableName').text(name).addClass('text-warning').removeClass('text-info'); $('#topbarTableLabel').text(name);
        $(`#table-${id}`).removeClass('table-new-order');
        $('#tableModal').modal('hide');
        $.get(`{{ url('/pos/table-order') }}/${id}`, function(res) {
            if (res.success && res.cart) { 
                cart = res.cart; 
                selectedOrderId = res.order_id; 
                $('#order_note').val(res.note || '');
                renderCart(); 
                showToast("Đã tải đơn từ bàn!"); 
            }
            else { cart = []; selectedOrderId = null; $('#order_note').val(''); renderCart(); }
        });
    }

    function addToCart(id, name, price) {
        if (!selectedTableId) setTakeaway();
        let item = cart.find(i => i.id === id);
        if (item) item.qty++; else cart.push({ id, name, price, qty: 1 });
        renderCart();
    }

    function renderCart() {
        subtotal = 0; let html = '';
        cart.forEach((item, idx) => {
            subtotal += item.price * item.qty;
            html += `<div class="cart-item animate__animated animate__fadeIn"><div style="flex:1"><div class="small fw-bold text-white text-uppercase">${item.name}</div><div class="x-small opacity-50">${item.price.toLocaleString()}đ</div></div><div class="qty-ctrl mx-2"><button class="qty-btn" onclick="updateQty(${idx}, -1)">−</button><span class="small fw-bold text-white px-2">${item.qty}</span><button class="qty-btn" onclick="updateQty(${idx}, 1)">+</button></div><div class="ms-2 fw-bold text-warning small" style="min-width:75px; text-align:right">${(item.price * item.qty).toLocaleString()}đ</div></div>`;
        });
        $('#cartContent').html(cart.length ? html : '<div class="text-center mt-5 opacity-25"><p>Giỏ hàng trống</p></div>');
        $('#subtotalVal').text(subtotal.toLocaleString() + 'đ');
        updateTotal();
    }

    function updateQty(idx, d) { cart[idx].qty += d; if (cart[idx].qty <= 0) cart.splice(idx, 1); renderCart(); }
    function setPayment(m, b) { $('.pay-method-btn').removeClass('active'); $(b).addClass('active'); paymentMethod = m; }

    function applyVoucher(code, val, type, min) {
        const minOrder = parseFloat(min) || 0;
        if (subtotal < minOrder) { alert(`Đơn chưa đủ ${minOrder.toLocaleString()}đ!`); return; }
        voucherData = { code, value: parseFloat(val) || 0, type };
        $('#selectedVoucherLabel').text(code).addClass('text-warning fw-bold');
        $('#selected_voucher_code').val(code);
        $('#voucherModal').modal('hide');
        updateTotal();
        showToast("Áp dụng Voucher thành công!");
    }

    function updateTotal() {
        let mPct = $('#manual_discount').val() || 0;
        let discountVal = 0;
        if (voucherData.code) {
            discountVal = (voucherData.type === 'percentage') ? (subtotal * voucherData.value / 100) : voucherData.value;
        }
        let manualVal = (subtotal * mPct / 100);
        totalValue = Math.max(0, subtotal - (discountVal + manualVal));
        $('#discountVal').text('-' + Math.round(discountVal + manualVal).toLocaleString() + 'đ');
        $('#totalAmount').text(Math.round(totalValue).toLocaleString() + 'đ');
    }

    function pressKey(v) { cashStr += v; $('#cashInput').val(parseInt(cashStr).toLocaleString()); }
    function clearKey() { cashStr = ""; $('#cashInput').val("0"); }
    function delKey() { cashStr = cashStr.slice(0, -1); $('#cashInput').val(cashStr ? parseInt(cashStr).toLocaleString() : "0"); }
    function quickCash() { cashStr = Math.round(totalValue).toString(); $('#cashInput').val(Math.round(totalValue).toLocaleString()); }

    function checkout() {
        if (!selectedTableId || cart.length === 0) { alert("Thiếu thông tin bàn/món!"); return; }
        const btn = $('#btnCheckout'); btn.prop('disabled', true).text('ĐANG XỬ LÝ...');
        $.ajax({
            url: "{{ route('pos.checkout') }}", method: "POST",
            data: { 
                table_id: selectedTableId, 
                order_id: selectedOrderId, 
                cart: cart, 
                total_amount: totalValue, 
                voucher_code: voucherData.code, 
                manual_discount: $('#manual_discount').val() || 0, 
                payment_method: paymentMethod,
                note: $('#order_note').val(),
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(res) {
                if (res.success) {
                    showToast('Thanh toán thành công!');
                    window.open(`{{ url('/pos/print-receipt') }}/${res.order_id}`, '_blank');
                    setTimeout(() => location.reload(), 1000);
                } else { alert("Lỗi: " + res.message); btn.prop('disabled', false).text('XÁC NHẬN THANH TOÁN'); }
            },
            error: function(xhr) { alert("Lỗi kết nối máy chủ!"); btn.prop('disabled', false).text('XÁC NHẬN THANH TOÁN'); }
        });
    }

    function confirmReset() { if(confirm('Hủy đơn hàng này?')) { cart = []; renderCart(); } }

    function checkNewOrders() {
        $.get("{{ route('tables.fetch_status') }}", function(tables) {
            let hasNew = false; let occCount = 0;
            tables.forEach(table => {
                const el = $(`#table-${table.id}`); const b = $(`#badge-${table.id}`);
                if (table.status === 'occupied') {
                    occCount++; if (!el.hasClass('border-danger')) { el.addClass('table-new-order'); hasNew = true; }
                    el.removeClass('border-success text-success').addClass('border-danger text-danger'); el.find('.status-label').text('Có khách'); b.removeClass('d-none');
                } else { el.removeClass('border-danger text-danger table-new-order').addClass('border-success text-success'); el.find('.status-label').text('Trống'); b.addClass('d-none'); }
            });
            if (occCount > 0) $('#mainBadge').text(occCount).removeClass('d-none'); else $('#mainBadge').addClass('d-none');
            if (hasNew) { const au = document.getElementById('notifSound'); au.currentTime = 0; au.play().catch(e => {}); $('#btnMainTable').addClass('animate__animated animate__flash animate__infinite text-danger'); }
        });
    }
    setInterval(checkNewOrders, 5000);
</script>
</body>
</html>