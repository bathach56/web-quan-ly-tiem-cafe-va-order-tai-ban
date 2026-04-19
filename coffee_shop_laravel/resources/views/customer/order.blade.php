<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Thực Đơn Gọi Món | {{ $shop_setting->shop_name ?? 'Hutech Coffee' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { 
            --primary: #d97706; 
            --primary-dark: #b45309;
            --bg-coffee: #fdf8f5;
        }
        
        body { 
            font-family: 'Lexend', sans-serif; 
            background: var(--bg-coffee); 
            padding-bottom: 110px;
            overflow-x: hidden;
            color: #292524;
        }

        /* Glassmorphism Header */
        .header { 
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            padding: 18px 20px; 
            box-shadow: 0 4px 30px rgba(0,0,0,0.05); 
            position: sticky; 
            top: 0; 
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .table-badge {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
            font-size: 0.9rem;
        }

        /* Danh mục động */
        .category-scroller {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 15px 5px;
            scrollbar-width: none;
        }
        .category-scroller::-webkit-scrollbar { display: none; }
        
        .cat-item {
            background: white;
            padding: 10px 24px;
            border-radius: 50px;
            white-space: nowrap;
            font-weight: 600;
            color: #57534e;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cat-item.active {
            background: var(--primary);
            color: white;
            transform: scale(1.08);
            box-shadow: 0 5px 15px rgba(217, 119, 6, 0.3);
        }

        /* Thẻ món ăn hiện đại */
        .product-card { 
            background: white; 
            border-radius: 24px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.04); 
            margin-bottom: 18px; 
            padding: 12px; 
            display: flex; 
            border: 1px solid rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .product-card:active { transform: scale(0.97); }
        
        .product-img { 
            width: 100px; 
            height: 100px; 
            border-radius: 18px; 
            object-fit: cover; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .product-info { flex: 1; padding-left: 15px; }
        .product-name { font-weight: 800; font-size: 1.05rem; line-height: 1.3; margin-bottom: 4px; }
        .price { color: var(--primary); font-weight: 800; font-size: 1.15rem; }

        /* Điều khiển số lượng */
        .qty-control { 
            display: flex; 
            align-items: center; 
            background: #f5f5f4; 
            border-radius: 50px; 
            padding: 4px; 
        }
        .qty-btn { 
            width: 32px; 
            height: 32px; 
            border: none; 
            background: white; 
            border-radius: 50%; 
            font-size: 1.1rem; 
            font-weight: 800; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Giỏ hàng nổi (Floating Dock) */
        .floating-cart {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 500px;
            background: rgba(41, 37, 36, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 15px 22px;
            color: white;
            display: none;
            justify-content: space-between;
            align-items: center;
            z-index: 1100;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.15);
        }

        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .pop-animation { animation: pop 0.3s ease; }
    </style>
</head>
<body>

<div class="header animate__animated animate__fadeInDown">
    <div class="d-flex justify-content-between align-items-center">
        <div class="table-badge">
            <i class="fa-solid fa-mug-hot me-1"></i> Bàn {{ $table->name }}
        </div>
        <div class="text-center">
            <h6 class="fw-800 m-0">THỰC ĐƠN</h6>
            <small class="text-muted" style="font-size: 9px; letter-spacing: 1px;">PREMIUM QUALITY</small>
        </div>
        <div class="rounded-circle border d-flex align-items-center justify-content-center" style="width:40px;height:40px; background: white;">
            <i class="fa-solid fa-heart text-danger"></i>
        </div>
    </div>
</div>

<div class="container mt-2">
    <div class="category-scroller animate__animated animate__fadeIn">
        <div class="cat-item active" onclick="filterCategory('all', this)">Tất cả</div>
        @foreach($categories as $cat)
            <div class="cat-item" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</div>
        @endforeach
    </div>

    <div class="mt-3" id="menuList">
        @forelse($products as $pro)
            <div class="product-card product-item animate__animated animate__fadeInUp" data-category="{{ $pro->category_id }}">
                <img src="{{ asset('img/' . $pro->image) }}" class="product-img" onerror="this.src='https://placehold.co/200x200?text=Premium+Coffee'">
                <div class="product-info d-flex flex-column justify-content-between">
                    <div>
                        <div class="product-name">{{ $pro->name }}</div>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Hương vị thơm ngon, đậm đà khó cưỡng.</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="price">{{ number_format($pro->price, 0, ',', '.') }}đ</div>
                        <div class="qty-control">
                            <button class="qty-btn" onclick="updateCart({{ $pro->id }}, -1, this)">-</button>
                            <span class="fw-800 mx-3" id="qty-{{ $pro->id }}" style="min-width: 15px; text-align: center;">0</span>
                            <button class="qty-btn" onclick="updateCart({{ $pro->id }}, 1, this)">+</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 opacity-50">
                <i class="fa-solid fa-hourglass-start fa-3x mb-3"></i>
                <p>Đang chuẩn bị thực đơn mới...</p>
            </div>
        @endforelse
    </div>
</div>

<div class="floating-cart animate__animated" id="floatingCart" onclick="showCartModal()">
    <div class="d-flex align-items-center">
        <div class="position-relative me-3">
            <i class="fa-solid fa-shopping-basket fa-xl"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" id="cartBadge">0</span>
        </div>
        <div>
            <div class="fw-bold" style="font-size: 0.85rem;">Xem đơn hàng</div>
            <small class="text-white-50" id="totalCountText">0 món</small>
        </div>
    </div>
    <div class="text-end d-flex align-items-center">
        <div class="fw-800 fs-5 me-2" id="totalPriceText">0đ</div>
        <i class="fa-solid fa-chevron-right opacity-50"></i>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-800"><i class="fa-solid fa-list-check text-warning me-2"></i>Xác nhận đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4" id="cartDetails">
                </div>
            <div class="p-4 border-top">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold text-muted">Tổng cộng:</span>
                    <span class="fw-800 fs-3 text-danger" id="modalTotalPrice">0đ</span>
                </div>
                <button class="btn btn-warning w-100 py-3 rounded-pill fw-800 shadow animate__animated animate__pulse animate__infinite" id="btnConfirm" onclick="sendOrder()">
                    GỬI ĐƠN ĐẶT MÓN <i class="fa-solid fa-paper-plane ms-2"></i>
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
        
        // Hiệu ứng Visual Feedback
        qtyDisplay.classList.remove('pop-animation');
        void qtyDisplay.offsetWidth; 
        qtyDisplay.classList.add('pop-animation');

        refreshUI();
    }

    function refreshUI() {
        let totalQty = 0; let totalPrice = 0;
        Object.values(cart).forEach(i => {
            totalQty += i.qty;
            totalPrice += (i.qty * i.price);
        });

        const cartBar = document.getElementById('floatingCart');
        if (totalQty > 0) {
            cartBar.style.display = 'flex';
            cartBar.classList.add('animate__fadeInUp');
            document.getElementById('cartBadge').textContent = totalQty;
            document.getElementById('totalCountText').textContent = `${totalQty} món đã chọn`;
            document.getElementById('totalPriceText').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
        } else {
            cartBar.style.display = 'none';
        }
    }

    function showCartModal() {
        let html = ''; let total = 0;
        Object.values(cart).forEach(i => {
            if (i.qty > 0) {
                total += (i.qty * i.price);
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                        <div>
                            <div class="fw-bold">${i.name}</div>
                            <small class="text-muted">${i.price.toLocaleString()}đ x ${i.qty}</small>
                        </div>
                        <div class="fw-800 text-primary">${(i.price * i.qty).toLocaleString()}đ</div>
                    </div>`;
            }
        });
        document.getElementById('cartDetails').innerHTML = html || '<p class="text-center text-muted">Chưa có món nào được chọn</p>';
        document.getElementById('modalTotalPrice').textContent = total.toLocaleString() + 'đ';
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    // HÀM GỬI ĐƠN QUAN TRỌNG (ĐÃ FIX LỖI KẾT NỐI)
    async function sendOrder() {
        const btn = document.getElementById('btnConfirm');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';

        try {
            // Lấy token từ thẻ meta
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch("{{ route('customer.order.submit') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": token // Gửi kèm token để Laravel xác thực
                },
                body: JSON.stringify({ 
                    table_id: tableId, 
                    cart: Object.values(cart).filter(i => i.qty > 0) 
                })
            });

            const res = await response.json();
            
            if (response.ok && res.success) {
                alert(`✅ Tuyệt vời! Đơn hàng của Bàn ${ "{{ $table->name }}" } đã được gửi thành công. Nhân viên sẽ phục vụ bạn ngay!`);
                location.reload();
            } else {
                alert("❌ Lỗi hệ thống: " + (res.message || "Vui lòng gọi nhân viên hỗ trợ."));
            }
        } catch (e) {
            console.error(e);
            alert("❌ Lỗi kết nối: Vui lòng kiểm tra mạng của bạn hoặc gọi nhân viên phục vụ!");
        } finally {
            btn.disabled = false;
            btn.innerText = 'GỬI ĐƠN ĐẶT MÓN';
        }
    }

    function filterCategory(catId, btn) {
        document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.product-item').forEach(item => {
            item.style.display = (catId === 'all' || item.dataset.category == catId) ? 'flex' : 'none';
        });
    }
</script>
</body>
</html>