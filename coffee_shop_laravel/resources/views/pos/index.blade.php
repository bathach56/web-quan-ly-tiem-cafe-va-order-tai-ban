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

    <style>
        /* GIỮ NGUYÊN PHẦN STYLE CỦA THỊNH */
        :root {
            --amber:        #f59e0b;
            --amber-dark:   #b45309;
            --amber-light:  #fef3c7;
            --panel-bg:     #1a1816;
            --panel-mid:    #242220;
            --panel-light:  #2e2b28;
            --panel-border: #3d3935;
            --text-muted:   #78716c;
            --success:      #22c55e;
            --danger:       #ef4444;
            --info:         #38bdf8;
            --surface:      #ffffff;
            --surface-2:    #f9f6f3;
            --border-light: #e7e2dd;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--surface-2);
            overflow: hidden;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        .pos-wrapper {
            display: grid;
            grid-template-columns: 1fr 420px;
            height: 100vh;
            overflow: hidden;
        }

        .pos-left {
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border-right: 1px solid var(--border-light);
            overflow: hidden;
        }

        .pos-topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-light);
            background: var(--surface);
            flex-shrink: 0;
        }

        .pos-topbar .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .pos-topbar .logo-icon {
            width: 44px;
            height: 44px;
            background: var(--amber);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            overflow: hidden;
        }
        .pos-topbar .logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .pos-topbar .brand-name {
            font-size: 1rem;
            font-weight: 800;
            color: #1c1917;
            line-height: 1.1;
        }
        .pos-topbar .brand-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .search-wrap {
            flex: 1;
            position: relative;
        }
        .search-wrap .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
            pointer-events: none;
        }
        .search-wrap input {
            width: 100%;
            height: 48px;
            border: 1.5px solid var(--border-light);
            border-radius: 14px;
            padding: 0 16px 0 42px;
            font-size: 0.9rem;
            background: var(--surface-2);
            outline: none;
            transition: border-color 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .search-wrap input:focus { border-color: var(--amber); }

        .btn-table {
            height: 48px;
            padding: 0 20px;
            background: var(--amber);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: background 0.2s, transform 0.1s;
            flex-shrink: 0;
            cursor: pointer;
        }
        .btn-table:hover { background: var(--amber-dark); }
        .btn-table .table-badge {
            background: white;
            color: var(--amber-dark);
            border-radius: 8px;
            padding: 2px 8px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .cat-row {
            display: flex;
            gap: 8px;
            padding: 14px 20px;
            overflow-x: auto;
            flex-shrink: 0;
            scrollbar-width: none;
            border-bottom: 1px solid var(--border-light);
        }
        .cat-row::-webkit-scrollbar { display: none; }

        .cat-btn {
            height: 44px;
            padding: 0 18px;
            border: 1.5px solid var(--border-light);
            border-radius: 12px;
            background: var(--surface);
            color: #57534e;
            font-weight: 600;
            font-size: 0.82rem;
            white-space: nowrap;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cat-btn.active {
            background: var(--amber);
            border-color: var(--amber);
            color: white;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }

        .product-area {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px 20px;
            scrollbar-width: thin;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
        }

        .product-card {
            background: var(--surface);
            border: 1.5px solid var(--border-light);
            border-radius: 18px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
        }
        .product-card:hover { border-color: var(--amber); transform: translateY(-3px); }

        .product-img-wrap { width: 100%; aspect-ratio: 4/3; overflow: hidden; background: var(--surface-2); }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: cover; }

        .product-info { padding: 10px 12px 12px; }
        .product-name { font-size: 0.82rem; font-weight: 700; color: #1c1917; text-transform: uppercase; margin-bottom: 4px; }
        .product-price { font-size: 0.92rem; font-weight: 800; color: var(--amber-dark); }

        .pos-right {
            display: flex;
            flex-direction: column;
            background: var(--panel-bg);
            color: white;
            overflow: hidden;
        }

        .cart-header { padding: 16px 20px; border-bottom: 1px solid var(--panel-border); }
        .table-label { display: flex; align-items: center; gap: 10px; }
        .table-label .tl-icon { width: 40px; height: 40px; background: var(--amber); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .table-label .tl-name { font-size: 1rem; font-weight: 800; color: var(--amber); }

        .btn-reset {
            width: 40px; height: 40px; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 12px; color: #a8a29e; display: flex; align-items: center; justify-content: center; cursor: pointer;
        }

        .cart-list { flex: 1; overflow-y: auto; padding: 10px 16px; }
        .cart-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--panel-border); }

        .qty-ctrl { display: flex; align-items: center; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 10px; }
        .qty-btn { width: 34px; height: 34px; background: none; border: none; color: var(--amber); font-size: 18px; cursor: pointer; }
        .qty-num { width: 32px; text-align: center; font-size: 0.9rem; font-weight: 700; color: white; }

        .promo-strip { padding: 10px 16px; display: flex; gap: 10px; }
        .btn-voucher { height: 42px; width: 100%; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 10px; color: #a8a29e; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; }

        .totals-area { padding: 12px 16px; background: var(--panel-mid); }
        .total-main { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--panel-border); }
        .total-main .val { font-size: 1.6rem; font-weight: 900; color: var(--amber); }

        .pay-methods { display: flex; gap: 8px; padding: 10px 16px; }
        .pay-method-btn { flex: 1; height: 44px; background: var(--panel-light); border: 1.5px solid var(--panel-border); border-radius: 12px; color: #78716c; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .pay-method-btn.active { background: rgba(245,158,11,0.12); border-color: var(--amber); color: var(--amber); }

        .cash-display { width: 100%; height: 54px; background: var(--panel-light); border: 1.5px solid var(--panel-border); border-radius: 14px; color: var(--amber); font-size: 1.6rem; font-weight: 800; text-align: right; padding: 0 16px; outline: none; }

        .keypad { display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px; padding: 8px 16px; }
        .key { height: 52px; background: var(--panel-light); border: 1px solid var(--panel-border); border-radius: 12px; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .key.k-clear { background: #7f1d1d; border-color: #991b1b; color: #fca5a5; }
        .key.k-exact { background: rgba(34,197,94,0.15); border-color: #166534; color: #4ade80; }
        .key.span2 { grid-column: span 2; }

        .btn-checkout { width: 100%; height: 58px; background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dark) 100%); border: none; border-radius: 16px; color: white; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 6px 20px rgba(245,158,11,0.35); }

        /* Style cho nút Logout mới */
        .btn-exit {
            width: 48px; height: 48px; background: var(--surface-2); border: 1.5px solid var(--border-light);
            border-radius: 14px; color: var(--text-muted); display: flex; align-items: center; 
            justify-content: center; cursor: pointer; transition: 0.2s; text-decoration: none; flex-shrink: 0;
        }
        .btn-exit:hover { border-color: var(--danger); color: var(--danger); background: #fff1f2; }

        .modal-content { border: none; border-radius: 22px; overflow: hidden; }
        .pos-toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); background: #1c1917; color: white; padding: 12px 24px; border-radius: 12px; z-index: 9999; opacity: 0; pointer-events: none; transition: 0.3s; }
        .pos-toast.show { opacity: 1; }
    </style>
</head>
<body>

<div class="pos-wrapper">

    <div class="pos-left">
        <div class="pos-topbar">
            <div class="logo-area">
                <div class="logo-icon">
                    @if(isset($shop_setting) && $shop_setting->logo && file_exists(public_path('img/' . $shop_setting->logo)))
                        <img src="{{ asset('img/' . $shop_setting->logo) }}">
                    @else
                        <i class="fa-solid fa-mug-hot"></i>
                    @endif
                </div>
                <div>
                    <div class="brand-name text-truncate" style="max-width: 150px;">{{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</div>
                    <div class="brand-sub">Nhóm 3 - POS System</div>
                </div>
            </div>

            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="productSearch" placeholder="Tìm tên món..." onkeyup="filterProducts()">
            </div>

            <button class="btn-table" data-bs-toggle="modal" data-bs-target="#tableModal">
                <i class="fa-solid fa-couch"></i>
                <span>BÀN</span>
                <span class="table-badge" id="topbarTableLabel">—</span>
            </button>

            <a href="#" class="btn-exit" title="Đăng xuất" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>

        <div class="cat-row">
            <button class="cat-btn active" onclick="filterCategory('all', this)">Tất cả</button>
            @foreach($categories as $cat)
                <button class="cat-btn" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button>
            @endforeach
        </div>

        <div class="product-area">
            <div class="product-grid" id="productList">
                @foreach($products as $pro)
                <div class="product-item" data-category="{{ $pro->category_id }}" data-name="{{ strtolower($pro->name) }}">
                    <div class="product-card" onclick="addToCart({{ $pro->id }}, '{{ addslashes($pro->name) }}', {{ $pro->price }})">
                        <div class="product-img-wrap">
                            <img src="{{ asset('img/'.$pro->image) }}" onerror="this.src='https://placehold.co/400x300?text=Coffee'">
                        </div>
                        <div class="product-info">
                            <div class="product-name text-truncate">{{ $pro->name }}</div>
                            <div class="product-price">{{ number_format($pro->price) }}đ</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="pos-right">
        <div class="cart-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="table-label">
                    <div class="tl-icon"><i class="fa-solid fa-receipt"></i></div>
                    <div>
                        <div class="tl-name" id="selectedTableName">CHỌN BÀN...</div>
                        <div style="font-size:0.7rem; color:#a8a29e;">NV: {{ Auth::user()->name }}</div>
                    </div>
                </div>
                <button class="btn-reset" onclick="confirmReset()" title="Hủy đơn"><i class="fa-solid fa-rotate-right"></i></button>
            </div>
        </div>

        <div class="cart-list" id="cartContent">
            <div class="text-center mt-5 opacity-25"><i class="fa-solid fa-basket-shopping fa-3x mb-2"></i><p>Giỏ hàng trống</p></div>
        </div>

        <div class="promo-strip">
            <div style="flex:1">
                <div style="font-size:0.65rem; color:#a8a29e; text-transform:uppercase; margin-bottom:4px;">Giảm (%)</div>
                <input type="number" id="manual_discount" class="cash-display" style="height:42px; font-size:1rem;" placeholder="0" onchange="updateTotal()">
            </div>
            <div style="flex:1">
                <div style="font-size:0.65rem; color:#a8a29e; text-transform:uppercase; margin-bottom:4px;">Voucher</div>
                <button class="btn-voucher" id="voucherBtn" data-bs-toggle="modal" data-bs-target="#voucherModal">
                    <span id="selectedVoucherLabel" class="text-truncate">Chọn mã</span>
                </button>
                <input type="hidden" id="selected_voucher_code">
            </div>
        </div>

        <div class="totals-area">
            <div class="total-row d-flex justify-content-between small opacity-75">
                <span>Tạm tính</span> <span id="subtotalVal">0đ</span>
            </div>
            <div class="total-row d-flex justify-content-between small text-danger">
                <span>Giảm giá</span> <span id="discountVal">-0đ</span>
            </div>
            <div class="total-main">
                <span class="fw-800">TỔNG CỘNG</span>
                <span class="val" id="totalAmount">0đ</span>
            </div>

            <div class="pay-methods mt-2">
                <button class="pay-method-btn active" onclick="setPayment('cash', this)">TIỀN MẶT</button>
                <button class="pay-method-btn" onclick="setPayment('card', this)">THẺ/POS</button>
                <button class="pay-method-btn" onclick="setPayment('banking', this)">BANKING</button>
            </div>

            <input type="text" id="cashInput" class="cash-display mt-2" placeholder="0" readonly>

            <div class="keypad mt-2">
                <button class="key" onclick="pressKey('1')">1</button>
                <button class="key" onclick="pressKey('2')">2</button>
                <button class="key" onclick="pressKey('3')">3</button>
                <button class="key k-clear" onclick="clearKey()">C</button>
                <button class="key" onclick="pressKey('4')">4</button>
                <button class="key" onclick="pressKey('5')">5</button>
                <button class="key" onclick="pressKey('6')">6</button>
                <button class="key" onclick="delKey()"><i class="fa-solid fa-delete-left"></i></button>
                <button class="key" onclick="pressKey('7')">7</button>
                <button class="key" onclick="pressKey('8')">8</button>
                <button class="key" onclick="pressKey('9')">9</button>
                <button class="key" onclick="pressKey('0')">0</button>
                <button class="key span2" onclick="pressKey('000')">.000</button>
                <button class="key k-exact span2" onclick="quickCash()">VỪA ĐỦ</button>
            </div>

            <button class="btn-checkout mt-2" onclick="checkout()">XÁC NHẬN THANH TOÁN</button>
        </div>
    </div>
</div>

<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="fw-800 m-0">SƠ ĐỒ BÀN PHỤC VỤ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    @foreach($tables as $table)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="p-4 text-center border-2 border rounded-4 bg-white {{ $table->status === 'occupied' ? 'border-danger text-danger' : 'border-success text-success' }}" 
                             style="cursor:pointer" onclick="selectOrderTable('{{ $table->id }}', '{{ $table->name }}')">
                            <i class="fa-solid fa-mug-hot fa-2x mb-2"></i>
                            <div class="fw-bold">{{ $table->name }}</div>
                            <small class="text-uppercase" style="font-size:0.6rem">{{ $table->status === 'occupied' ? 'Có khách' : 'Trống' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary p-4">
                <h5 class="fw-800 m-0 text-warning"><i class="fa-solid fa-ticket-simple me-2"></i>KHO VOUCHER KHẢ DỤNG</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                @forelse($vouchers ?? [] as $v)
                <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center" style="cursor:pointer" 
                     onclick="applyVoucher('{{ $v->code }}', {{ $v->discount_value }}, '{{ $v->type }}', {{ $v->min_order_value }})">
                    <div style="flex:1">
                        <div class="fw-bold text-warning fs-5">{{ $v->code }}</div>
                        <div class="small fw-bold">{{ $v->name }}</div>
                        <div class="x-small text-info mt-1">HSD: {{ $v->end_date ? $v->end_date->format('d/m/Y') : 'Vô hạn' }} | Tối thiểu: {{ number_format($v->min_order_value) }}đ</div>
                    </div>
                    <div class="badge bg-danger fs-6 px-3 py-2">
                        -{{ $v->type == 'percentage' ? $v->discount_value.'%' : number_format($v->discount_value).'đ' }}
                    </div>
                </div>
                @empty
                <div class="p-5 text-center opacity-25">
                    <i class="fa-solid fa-ticket fa-3x mb-2"></i>
                    <p>Không có voucher nào khả dụng</p>
                </div>
                @endforelse
            </div>
            <div class="modal-footer border-secondary">
                <button class="btn btn-outline-light w-100 rounded-pill fw-bold" onclick="cancelVoucher()" data-bs-dismiss="modal">HỦY DÙNG VOUCHER</button>
            </div>
        </div>
    </div>
</div>

<div class="pos-toast" id="posToast"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let cart = [];
    let selectedTableId = null;
    let subtotal = 0, totalValue = 0, cashStr = "", paymentMethod = 'cash';
    let voucherData = { code: null, value: 0, type: 'fixed' };

    // --- FIX MODAL STACKING ---
    $(document).on('show.bs.modal', '.modal', function () { $(this).appendTo('body'); });

    function showToast(msg) {
        const t = $('#posToast');
        t.text(msg).addClass('show');
        setTimeout(() => t.removeClass('show'), 2000);
    }

    function filterProducts() {
        let kw = $('#productSearch').val().toLowerCase();
        $('.product-item').each(function() { $(this).toggle($(this).data('name').includes(kw)); });
    }

    function filterCategory(catId, btn) {
        $('.cat-btn').removeClass('active'); $(btn).addClass('active');
        $('.product-item').each(function() { $(this).toggle(catId === 'all' || $(this).data('category') == catId); });
    }

    function selectOrderTable(id, name) {
        selectedTableId = id;
        $('#selectedTableName').text(name);
        $('#topbarTableLabel').text(name);
        bootstrap.Modal.getInstance(document.getElementById('tableModal'))?.hide();
        renderCart();
        showToast(`Đã chọn ${name}`);
    }

    function addToCart(id, name, price) {
        if (!selectedTableId) {
            new bootstrap.Modal(document.getElementById('tableModal')).show();
            return;
        }
        let item = cart.find(i => i.id === id);
        if (item) item.qty++; else cart.push({ id, name, price, qty: 1 });
        renderCart();
        showToast(`Đã thêm: ${name}`);
    }

    function renderCart() {
        subtotal = 0; let html = '';
        cart.forEach((item, idx) => {
            subtotal += item.price * item.qty;
            html += `
                <div class="cart-item animate__animated animate__fadeInSmall">
                    <div class="ci-name">
                        <div class="n">${item.name}</div>
                        <div class="u">${item.price.toLocaleString()}đ</div>
                    </div>
                    <div class="qty-ctrl mx-2">
                        <button class="qty-btn" onclick="updateQty(${idx}, -1)">−</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${idx}, 1)">+</button>
                    </div>
                    <div class="ms-2 fw-bold text-warning small" style="min-width:65px; text-align:right">${(item.price * item.qty).toLocaleString()}đ</div>
                </div>`;
        });
        $('#cartContent').html(cart.length ? html : '<div class="text-center mt-5 opacity-25"><i class="fa-solid fa-basket-shopping fa-3x mb-2"></i><p>Giỏ hàng trống</p></div>');
        $('#subtotalVal').text(subtotal.toLocaleString() + 'đ');
        updateTotal();
    }

    function updateQty(idx, d) { cart[idx].qty += d; if (cart[idx].qty <= 0) cart.splice(idx, 1); renderCart(); }

    function applyVoucher(code, val, type, min) {
        if (subtotal < min) return alert(`Đơn hàng chưa đủ mức tối thiểu ${min.toLocaleString()}đ!`);
        voucherData = { code, value: val, type };
        $('#selectedVoucherLabel').text(code).addClass('text-warning fw-bold');
        $('#selected_voucher_code').val(code);
        bootstrap.Modal.getInstance(document.getElementById('voucherModal'))?.hide();
        updateTotal();
        showToast(`Đã áp dụng mã ${code}`);
    }

    function cancelVoucher() {
        voucherData = { code: null, value: 0, type: 'fixed' };
        $('#selectedVoucherLabel').text('Chọn mã').removeClass('text-warning fw-bold');
        $('#selected_voucher_code').val('');
        updateTotal();
    }

    function updateTotal() {
        let mPct = $('#manual_discount').val() || 0;
        let mVal = (subtotal * mPct) / 100;
        let vVal = (voucherData.code) ? (voucherData.type === 'percentage' ? (subtotal * voucherData.value / 100) : voucherData.value) : 0;
        let tDisc = mVal + vVal;
        totalValue = Math.max(0, subtotal - tDisc);
        $('#discountVal').text('-' + Math.round(tDisc).toLocaleString() + 'đ');
        $('#totalAmount').text(Math.round(totalValue).toLocaleString() + 'đ');
    }

    function pressKey(v) { cashStr += v; $('#cashInput').val(parseInt(cashStr).toLocaleString()); }
    function clearKey() { cashStr = ""; $('#cashInput').val("0"); }
    function delKey() { cashStr = cashStr.slice(0, -1); $('#cashInput').val(cashStr ? parseInt(cashStr).toLocaleString() : "0"); }
    function quickCash() { cashStr = Math.round(totalValue).toString(); $('#cashInput').val(Math.round(totalValue).toLocaleString()); }
    function setPayment(m, b) { $('.pay-method-btn').removeClass('active'); $(b).addClass('active'); paymentMethod = m; }

    function checkout() {
        if (!selectedTableId || !cart.length) return alert("Thiếu bàn hoặc món ăn!");
        const btn = $('.btn-checkout');
        btn.prop('disabled', true).text('ĐANG XỬ LÝ...');
        $.ajax({
            url: "{{ route('pos.checkout') }}",
            method: "POST",
            data: { 
                table_id: selectedTableId, 
                cart: cart, 
                payment_method: paymentMethod, 
                total_amount: totalValue, 
                voucher_code: voucherData.code,
                manual_discount: $('#manual_discount').val() || 0,
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(res) {
                if (res.success) {
                    showToast('Thanh toán thành công!');
                    window.open(`/pos/print-receipt/${res.order_id}`, '_blank');
                    location.reload();
                } else {
                    alert(res.message);
                    btn.prop('disabled', false).text('XÁC NHẬN THANH TOÁN');
                }
            },
            error: function() { 
                alert("Lỗi kết nối máy chủ!"); 
                btn.prop('disabled', false).text('XÁC NHẬN THANH TOÁN');
            }
        });
    }

    function confirmReset() { if(confirm('Xóa đơn hàng hiện tại?')) { cart = []; cancelVoucher(); renderCart(); } }
</script>
</body>
</html>