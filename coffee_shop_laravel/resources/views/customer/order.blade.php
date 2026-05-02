<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Thực Đơn Gọi Món | {{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { 
            --primary: #6f4e37; 
            --primary-light: #935d2d;
            --bg-coffee: #fdf8f5;
        }
        
        body { 
            font-family: 'Lexend', sans-serif; 
            background: var(--bg-coffee); 
            padding-bottom: 120px;
            color: #292524;
            overflow-x: hidden;
        }

        /* --- SỬA LỖI CĂN GIỮA & TRÀN MÀN HÌNH --- */
        .modal { padding-right: 0 !important; }
        .modal-open { padding-right: 0 !important; }

        /* Modern Sticky Header */
        .header { 
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 15px 20px; 
            box-shadow: 0 4px 20px rgba(111, 78, 55, 0.08); 
            position: sticky; 
            top: 0; 
            z-index: 1000;
            border-bottom: 1px solid rgba(111, 78, 55, 0.1);
        }

        .table-badge {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Category Slider */
        .category-scroller {
            display: flex; gap: 10px; overflow-x: auto; padding: 15px 20px; scrollbar-width: none;
        }
        .category-scroller::-webkit-scrollbar { display: none; }
        
        .cat-item {
            background: white; padding: 10px 22px; border-radius: 18px; white-space: nowrap;
            font-weight: 600; color: #78716c; border: 1px solid #e7e5e4;
        }
        .cat-item.active { background: var(--primary); color: white; border-color: var(--primary); }

        /* Product Cards */
        .product-card { 
            background: white; border-radius: 24px; margin: 0 20px 18px; padding: 12px; display: flex; 
            border: 1px solid rgba(111, 78, 55, 0.05);
        }
        .product-img { width: 100px; height: 100px; border-radius: 20px; object-fit: cover; }
        .product-info { flex: 1; padding-left: 15px; }
        .product-name { font-weight: 700; font-size: 1rem; color: #1c1917; text-transform: uppercase; }
        .price { color: var(--primary); font-weight: 800; font-size: 1.1rem; }

        /* Quantity Control */
        .qty-control { display: flex; align-items: center; background: #f5f5f4; border-radius: 50px; padding: 3px; }
        .qty-btn { width: 32px; height: 32px; border: none; background: white; border-radius: 50%; color: var(--primary); font-weight: 800; }

        /* --- SỬA LỖI LỆCH GIỎ HÀNG --- */
        .floating-cart {
            position: fixed;
            bottom: 25px;
            left: 20px !important;
            right: 20px !important;
            width: auto !important;
            transform: none !important;
            background: rgba(28, 25, 23, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 22px;
            padding: 15px 20px;
            color: white;
            display: none;
            justify-content: space-between;
            align-items: center;
            z-index: 1100;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
        }

        .success-icon { width: 80px; height: 80px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; }
        .btn-back-menu { background: var(--primary); color: white; border-radius: 50px; padding: 12px 30px; font-weight: 700; border: none; }
        
        @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.3); } 100% { transform: scale(1); } }
        .pop-animation { animation: pop 0.3s ease; }
    </style>
</head>
<body>

<div class="header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="table-badge"><i class="fa-solid fa-couch me-1"></i>{{ $table->name }}</div>
        <div class="text-center">
            <h6 class="fw-800 m-0 text-uppercase">HUTECH Coffee</h6>
            <small class="text-muted" style="font-size: 8px; letter-spacing: 2px;">SINCE 2026</small>
        </div>
        <div class="rounded-circle border d-flex align-items-center justify-content-center" style="width:38px;height:38px; background: white;">
            <i class="fa-solid fa-mug-hot text-coffee"></i>
        </div>
    </div>
</div>

<div class="category-scroller">
    <div class="cat-item active" onclick="filterCategory('all', this)">Tất cả</div>
    @foreach($categories as $cat)
        <div class="cat-item" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</div>
    @endforeach
</div>

<div id="menuList">
    @forelse($products as $pro)
        <div class="product-card product-item" data-category="{{ $pro->category_id }}">
            <img src="{{ asset('img/' . $pro->image) }}" class="product-img" onerror="this.src='https://placehold.co/300x300?text=Coffee'">
            <div class="product-info d-flex flex-column justify-content-between">
                <div class="product-name">{{ $pro->name }}</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="price">{{ number_format($pro->price, 0, ',', '.') }}đ</div>
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateCart({{ $pro->id }}, -1, this)">-</button>
                        <span class="fw-800 mx-3" id="qty-{{ $pro->id }}">0</span>
                        <button class="qty-btn" onclick="updateCart({{ $pro->id }}, 1, this)">+</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 opacity-50"><p>Đang tải món...</p></div>
    @endforelse
</div>

<!-- Floating Cart -->
<div class="floating-cart animate__animated" id="floatingCart" onclick="showCartModal()">
    <div class="d-flex align-items-center">
        <div class="position-relative me-3">
            <i class="fa-solid fa-shopping-basket fa-xl text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" id="cartBadge" style="font-size: 0.6rem;">0</span>
        </div>
        <div>
            <div class="fw-bold" style="font-size: 0.85rem;">Xem đơn hàng</div>
            <small class="text-white-50" id="totalCountText" style="font-size: 0.7rem;">0 món</small>
        </div>
    </div>
    <div class="fw-800 fs-5" id="totalPriceText">0đ</div>
</div>

<!-- Modal Cart -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 92%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-800">GIỎ HÀNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="cartDetails" style="max-height: 30vh; overflow-y: auto;" class="mb-4"></div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Ghi chú cho quán:</label>
                    <textarea id="customer_note" class="form-control border-0 bg-light" rows="2" placeholder="VD: ít đá, ít đường..." style="border-radius: 15px; font-size: 0.85rem;"></textarea>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <span class="fw-bold text-secondary">Tổng cộng:</span>
                    <span class="fw-800 fs-2 text-danger" id="modalTotalPrice">0đ</span>
                </div>
            </div>
            <div class="p-4 pt-0">
                <button class="btn btn-warning w-100 py-3 rounded-pill fw-800" id="btnConfirm" onclick="sendOrder()">XÁC NHẬN ĐẶT MÓN</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 85%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-body text-center py-5">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <h4 class="fw-800 mb-2">ĐẶT MÓN THÀNH CÔNG!</h4>
                <p class="text-muted mb-4 small">Đơn hàng đã được gửi đến quầy pha chế.</p>
                <button class="btn-back-menu w-100" onclick="location.reload()">TIẾP TỤC</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = {};
    const tableId = "{{ $table->id }}";

    // --- FIX BUG CHỒNG BACKDROP ---
    const cartModalEl = document.getElementById('cartModal');
    const successModalEl = document.getElementById('successModal');

    function updateCart(id, name, price, delta) {
        if (!cart[id]) cart[id] = { id, name, price, qty: 0 };
        cart[id].qty = Math.max(0, cart[id].qty + delta);
        
        document.getElementById(`qty-${id}`).textContent = cart[id].qty;
        refreshUI();
    }

    // Sửa lại hàm updateCart cho khớp với HTML mới
    function updateCart(id, delta, btn) {
        if (!cart[id]) {
            const card = btn.closest('.product-card');
            const name = card.querySelector('.product-name').textContent;
            const price = parseInt(card.querySelector('.price').textContent.replace(/\./g, ''));
            cart[id] = { id, name, price, qty: 0 };
        }
        cart[id].qty = Math.max(0, cart[id].qty + delta);
        document.getElementById(`qty-${id}`).textContent = cart[id].qty;
        refreshUI();
    }

    function refreshUI() {
        let totalQty = 0; let totalPrice = 0;
        Object.values(cart).forEach(i => { totalQty += i.qty; totalPrice += (i.qty * i.price); });
        const cartBar = document.getElementById('floatingCart');
        if (totalQty > 0) {
            cartBar.style.display = 'flex';
            document.getElementById('cartBadge').textContent = totalQty;
            document.getElementById('totalPriceText').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
            document.getElementById('totalCountText').textContent = `${totalQty} món đã chọn`;
        } else { cartBar.style.display = 'none'; }
    }

    function showCartModal() {
        let html = ''; let total = 0;
        Object.values(cart).forEach(i => {
            if (i.qty > 0) {
                total += (i.qty * i.price);
                html += `<div class="d-flex justify-content-between mb-3"><div class="fw-bold">${i.name} x${i.qty}</div><div>${(i.price * i.qty).toLocaleString()}đ</div></div>`;
            }
        });
        document.getElementById('cartDetails').innerHTML = html;
        document.getElementById('modalTotalPrice').textContent = total.toLocaleString() + 'đ';
        
        // DÙNG getOrCreateInstance ĐỂ FIX BUG CLICK LIÊN TỤC
        bootstrap.Modal.getOrCreateInstance(cartModalEl).show();
    }

    async function sendOrder() {
        const btn = document.getElementById('btnConfirm');
        btn.disabled = true;
        const note = document.getElementById('customer_note').value;

        try {
            const response = await fetch("{{ route('customer.order.submit') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ table_id: tableId, cart: Object.values(cart).filter(i => i.qty > 0), note: note })
            });
            const res = await response.json();
            if (res.success) {
                bootstrap.Modal.getOrCreateInstance(cartModalEl).hide();
                bootstrap.Modal.getOrCreateInstance(successModalEl).show();
            }
        } catch (e) { alert("Lỗi!"); btn.disabled = false; }
    }

    function filterCategory(catId, btn) {
        document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.product-item').forEach(item => { item.style.display = (catId === 'all' || item.dataset.category == catId) ? 'flex' : 'none'; });
    }
</script>
</body>
</html>