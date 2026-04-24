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
            --primary: #6f4e37; /* Màu nâu cà phê chủ đạo */
            --primary-light: #935d2d;
            --bg-coffee: #fdf8f5;
        }
        
        body { 
            font-family: 'Lexend', sans-serif; 
            background: var(--bg-coffee); 
            padding-bottom: 120px;
            color: #292524;
            user-select: none;
        }

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
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.3);
        }

        /* Category Slider */
        .category-scroller {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 15px 20px;
            scrollbar-width: none;
        }
        .category-scroller::-webkit-scrollbar { display: none; }
        
        .cat-item {
            background: white;
            padding: 10px 22px;
            border-radius: 18px;
            white-space: nowrap;
            font-weight: 600;
            color: #78716c;
            border: 1px solid #e7e5e4;
            transition: all 0.3s ease;
        }
        .cat-item.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 6px 15px rgba(111, 78, 55, 0.25);
            transform: scale(1.05);
        }

        /* Product Cards */
        .product-card { 
            background: white; 
            border-radius: 24px; 
            margin: 0 20px 18px; 
            padding: 12px; 
            display: flex; 
            border: 1px solid rgba(111, 78, 55, 0.05);
            transition: all 0.2s ease;
        }
        .product-card:active { transform: scale(0.96); }
        .product-img { width: 100px; height: 100px; border-radius: 20px; object-fit: cover; }
        .product-info { flex: 1; padding-left: 15px; }
        .product-name { font-weight: 700; font-size: 1rem; color: #1c1917; text-transform: uppercase; }
        .price { color: var(--primary); font-weight: 800; font-size: 1.1rem; }

        /* Quantity Control */
        .qty-control { display: flex; align-items: center; background: #f5f5f4; border-radius: 50px; padding: 3px; }
        .qty-btn { width: 32px; height: 32px; border: none; background: white; border-radius: 50%; color: var(--primary); font-weight: 800; box-shadow: 0 2px 5px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; }

        /* Floating Cart Dock */
        .floating-cart {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 450px;
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
        }

        /* Success Popup Styles */
        .success-card { text-align: center; padding: 40px 20px; }
        .success-icon { width: 80px; height: 80px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }
        .btn-back-menu { background: var(--primary); color: white; border-radius: 50px; padding: 12px 30px; font-weight: 700; border: none; transition: 0.3s; }

        @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.3); } 100% { transform: scale(1); } }
        .pop-animation { animation: pop 0.3s ease; }
    </style>
</head>
<body>

<div class="header animate__animated animate__fadeInDown">
    <div class="d-flex justify-content-between align-items-center">
        <div class="table-badge"><i class="fa-solid fa-couch me-1"></i> Bàn {{ $table->name }}</div>
        <div class="text-center">
            <h6 class="fw-800 m-0 text-uppercase">Thực Đơn</h6>
            <small class="text-muted" style="font-size: 8px; letter-spacing: 2px;">PREMIUM QUALITY</small>
        </div>
        <div class="rounded-circle border d-flex align-items-center justify-content-center shadow-sm" style="width:38px;height:38px; background: white;">
            <i class="fa-solid fa-mug-hot text-coffee"></i>
        </div>
    </div>
</div>

<div class="category-scroller animate__animated animate__fadeIn">
    <div class="cat-item active" onclick="filterCategory('all', this)">Tất cả</div>
    @foreach($categories as $cat)
        <div class="cat-item" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</div>
    @endforeach
</div>

<div id="menuList">
    @forelse($products as $pro)
        <div class="product-card product-item animate__animated animate__fadeInUp" data-category="{{ $pro->category_id }}">
            <img src="{{ asset('img/' . $pro->image) }}" class="product-img" onerror="this.src='https://placehold.co/300x300?text=Coffee'">
            <div class="product-info d-flex flex-column justify-content-between">
                <div>
                    <div class="product-name">{{ $pro->name }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Hương vị đậm đà truyền thống.</div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="price">{{ number_format($pro->price, 0, ',', '.') }}<small>đ</small></div>
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateCart({{ $pro->id }}, -1, this)">-</button>
                        <span class="fw-800 mx-3" id="qty-{{ $pro->id }}">0</span>
                        <button class="qty-btn" onclick="updateCart({{ $pro->id }}, 1, this)">+</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 opacity-50"><p class="fw-bold">Đang cập nhật thực đơn...</p></div>
    @endforelse
</div>

<div class="floating-cart animate__animated" id="floatingCart" onclick="showCartModal()">
    <div class="d-flex align-items-center">
        <div class="position-relative me-3">
            <i class="fa-solid fa-shopping-basket fa-xl text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" id="cartBadge" style="font-size: 0.6rem;">0</span>
        </div>
        <div>
            <div class="fw-bold" style="font-size: 0.8rem;">Xem đơn hàng</div>
            <small class="text-white-50" id="totalCountText" style="font-size: 0.7rem;">0 món</small>
        </div>
    </div>
    <div class="text-end d-flex align-items-center">
        <div class="fw-800 fs-5 me-2" id="totalPriceText">0đ</div>
        <i class="fa-solid fa-chevron-right text-white-50 fs-6"></i>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-800"><i class="fa-solid fa-clipboard-list text-coffee me-2"></i>XÁC NHẬN ĐƠN HÀNG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="cartDetails" style="max-height: 40vh; overflow-y: auto;">
                </div>
            <div class="p-4 border-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold text-secondary">Tổng thanh toán:</span>
                    <span class="fw-800 fs-2 text-danger" id="modalTotalPrice">0đ</span>
                </div>
                <button class="btn btn-warning w-100 py-3 rounded-pill fw-800 shadow-lg" id="btnConfirm" onclick="sendOrder()">
                    GỬI YÊU CẦU ĐẶT MÓN <i class="fa-solid fa-paper-plane ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mx-4">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-body success-card animate__animated animate__zoomIn">
                <div class="success-icon animate__animated animate__bounceIn animate__delay-1s">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h4 class="fw-800 text-dark mb-2">ĐẶT MÓN THÀNH CÔNG!</h4>
                <p class="text-muted mb-4">Đơn hàng của bạn đã được gửi đến quầy pha chế. Vui lòng đợi trong giây lát.</p>
                <button type="button" class="btn-back-menu w-100 shadow" onclick="location.reload()">
                    TIẾP TỤC GỌI MÓN <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = {};
    const tableId = "{{ $table->id }}";

    function updateCart(id, delta, btn) {
        if (!cart[id]) {
            const card = btn.closest('.product-card');
            const name = card.querySelector('.product-name').textContent;
            const price = parseInt(card.querySelector('.price').textContent.replace(/\./g, ''));
            cart[id] = { id, name, price, qty: 0 };
        }
        cart[id].qty = Math.max(0, cart[id].qty + delta);
        const qtyDisplay = document.getElementById(`qty-${id}`);
        qtyDisplay.textContent = cart[id].qty;
        qtyDisplay.classList.remove('pop-animation');
        void qtyDisplay.offsetWidth; 
        qtyDisplay.classList.add('pop-animation');
        refreshUI();
    }

    function refreshUI() {
        let totalQty = 0; let totalPrice = 0;
        Object.values(cart).forEach(i => { totalQty += i.qty; totalPrice += (i.qty * i.price); });
        const cartBar = document.getElementById('floatingCart');
        if (totalQty > 0) {
            cartBar.style.display = 'flex';
            if(!cartBar.classList.contains('animate__fadeInUp')) cartBar.classList.add('animate__fadeInUp');
            document.getElementById('cartBadge').textContent = totalQty;
            document.getElementById('totalCountText').textContent = `${totalQty} món đã chọn`;
            document.getElementById('totalPriceText').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
        } else { cartBar.style.display = 'none'; }
    }

    function showCartModal() {
        let html = ''; let total = 0;
        Object.values(cart).forEach(i => {
            if (i.qty > 0) {
                total += (i.qty * i.price);
                html += `<div class="d-flex justify-content-between mb-3"><div class="fw-bold">${i.name}</div><div class="fw-800">${(i.price * i.qty).toLocaleString()}đ</div></div>`;
            }
        });
        document.getElementById('cartDetails').innerHTML = html;
        document.getElementById('modalTotalPrice').textContent = total.toLocaleString() + 'đ';
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    async function sendOrder() {
        const btn = document.getElementById('btnConfirm');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ĐANG GỬI...';
        try {
            const response = await fetch("{{ route('customer.order.submit') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ table_id: tableId, cart: Object.values(cart).filter(i => i.qty > 0) })
            });
            const res = await response.json();
            if (response.ok && res.success) {
                bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                new bootstrap.Modal(document.getElementById('successModal')).show();
            } else { alert("Lỗi: " + res.message); btn.disabled = false; }
        } catch (e) { alert("Lỗi kết nối mạng!"); btn.disabled = false; }
    }

    function filterCategory(catId, btn) {
        document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.product-item').forEach(item => { item.style.display = (catId === 'all' || item.dataset.category == catId) ? 'flex' : 'none'; });
    }
</script>
</body>
</html>