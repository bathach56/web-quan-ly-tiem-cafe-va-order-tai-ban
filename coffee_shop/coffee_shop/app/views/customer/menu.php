<?php 
// GIAO DIỆN MOBILE APP DÀNH CHO KHÁCH HÀNG (Không dùng chung header/footer)
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu - <?= htmlspecialchars($table['table_name']) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* CSS Tùy chỉnh phong cách Mobile App */
        :root { --primary: #8d5524; --bg-color: #f4f6f8; }
        body { background-color: var(--bg-color); font-family: 'Segoe UI', sans-serif; padding-bottom: 90px; }
        
        /* Header & Table Info */
        .app-header { background: var(--primary); color: white; padding: 20px 15px 15px; position: sticky; top: 0; z-index: 1000; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .table-badge { background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        
        /* Category Scroll Ngang */
        .category-wrapper { display: flex; overflow-x: auto; gap: 10px; padding: 15px; scrollbar-width: none; }
        .category-wrapper::-webkit-scrollbar { display: none; }
        .cat-btn { white-space: nowrap; border: 1px solid var(--primary); color: var(--primary); background: white; padding: 6px 18px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .cat-btn.active { background: var(--primary); color: white; }
        
        /* Product Item Card */
        .product-item { background: white; border-radius: 15px; padding: 12px; margin: 0 15px 15px; display: flex; gap: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .product-img { width: 85px; height: 85px; border-radius: 12px; object-fit: cover; }
        .product-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .product-name { font-size: 1.05rem; font-weight: bold; margin: 0; color: #333; }
        .product-price { color: var(--primary); font-weight: bold; font-size: 1rem; }
        
        /* Nút Tăng Giảm Số Lượng */
        .qty-control { display: flex; align-items: center; gap: 10px; background: #f8f9fa; border-radius: 20px; padding: 2px; border: 1px solid #eee; width: fit-content; }
        .qty-btn { border: none; background: white; color: var(--primary); width: 28px; height: 28px; border-radius: 50%; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; }
        .qty-input { width: 25px; text-align: center; border: none; background: transparent; font-weight: bold; font-size: 0.95rem; }
        .add-btn { background: var(--primary); color: white; border: none; border-radius: 20px; padding: 4px 15px; font-weight: 600; font-size: 0.9rem; }
        
        /* Bottom Cart Bar */
        .bottom-bar { position: fixed; bottom: 0; left: 0; right: 0; background: white; padding: 15px 20px; box-shadow: 0 -4px 15px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; z-index: 1000; border-top-left-radius: 20px; border-top-right-radius: 20px; }
        .cart-icon-wrap { position: relative; font-size: 1.5rem; color: #333; margin-right: 15px; }
        .cart-badge { position: absolute; top: -6px; right: -8px; background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.7rem; font-weight: bold; }
        .total-price { font-size: 1.2rem; font-weight: bold; color: var(--primary); }
        .btn-checkout { background: var(--primary); color: white; border-radius: 25px; padding: 10px 25px; font-weight: bold; border: none; font-size: 1rem; box-shadow: 0 4px 10px rgba(141, 85, 36, 0.3); }
        
        /* Offcanvas Cart Modal */
        .offcanvas-bottom { height: 75vh !important; border-top-left-radius: 20px; border-top-right-radius: 20px; }

        /* ======================================= */
        /* CSS CHO POPUP ANIMATION ĐẶT MÓN THÀNH CÔNG */
        /* ======================================= */
        #successOverlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6); z-index: 10500;
            display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .success-modal {
            background: white; width: 85%; max-width: 340px;
            border-radius: 24px; padding: 35px 20px 25px; text-align: center;
            transform: scale(0.5); opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .success-modal.active { transform: scale(1); opacity: 1; }
        
        .success-icon-wrap {
            width: 70px; height: 70px; background: #28a745;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            animation: popIn 0.6s ease forwards;
            animation-delay: 0.1s;
            transform: scale(0);
        }
        .success-icon-wrap i { font-size: 35px; color: white; }
        
        @keyframes popIn {
            0% { transform: scale(0); }
            60% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="app-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1 fw-bold">Coffee Shop</h5>
            <small class="opacity-75">Thực đơn điện tử</small>
        </div>
        <div class="table-badge">
            <i class="fas fa-chair me-1"></i> <?= htmlspecialchars($table['table_name']) ?>
        </div>
    </div>

    <div class="px-3 py-3 d-flex gap-2">
        <div class="input-group bg-white rounded-pill overflow-hidden shadow-sm border border-light flex-grow-1">
            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Tìm món ăn, đồ uống...">
        </div>
        <button class="btn btn-warning rounded-pill text-dark fw-bold px-3 shadow-sm" onclick="callStaff()">
            <i class="fas fa-bell"></i> Gọi
        </button>
    </div>

    <div class="category-wrapper" id="categoryWrapper">
        </div>

    <div id="productList">
        </div>

    <div id="toastMessage" class="position-fixed top-50 start-50 translate-middle bg-dark text-white px-4 py-2 rounded-pill shadow" style="display: none; z-index: 9999; opacity: 0.9;">
        Đã thêm vào giỏ
    </div>

    <div class="bottom-bar" onclick="openCart()">
        <div class="d-flex align-items-center">
            <div class="cart-icon-wrap">
                <i class="fas fa-shopping-basket"></i>
                <span class="cart-badge" id="cartCount">0</span>
            </div>
            <div>
                <small class="text-muted d-block" style="font-size: 0.8rem;">Tổng cộng</small>
                <div class="total-price" id="cartTotal">0 đ</div>
            </div>
        </div>
        <button class="btn-checkout" onclick="event.stopPropagation(); openCart();">
            Xem Giỏ Hàng <i class="fas fa-chevron-right ms-1"></i>
        </button>
    </div>

    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Chi tiết Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" style="background-color: #f8f9fa;">
            <div id="cartItemsContainer" class="p-3">
                </div>
        </div>
        <div class="offcanvas-footer p-3 bg-white border-top">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold text-muted">Tổng thanh toán:</span>
                <span class="fw-bold fs-4" style="color: var(--primary);" id="modalTotal">0 đ</span>
            </div>
            <button class="btn btn-checkout w-100 py-3 fs-5" onclick="submitOrder()">
                <i class="fas fa-paper-plane me-2"></i> GỬI ORDER XUỐNG BẾP
            </button>
        </div>
    </div>

    <div id="successOverlay">
        <div class="success-modal" id="successModal">
            <div class="success-icon-wrap">
                <i class="fas fa-check"></i>
            </div>
            <h4 class="fw-bold text-success mb-2">Tuyệt vời!</h4>
            <p class="text-muted mb-4" style="font-size: 0.95rem;">Đơn hàng của bạn đã được gửi xuống bếp. Vui lòng đợi trong giây lát nhé.</p>
            <button class="btn btn-success w-100 rounded-pill py-2 fw-bold fs-5 shadow-sm" onclick="closeSuccessPopup()">
                Đồng ý
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dữ liệu từ PHP
        const products = <?= json_encode($products) ?>;
        const assetImg = '<?= ASSET_IMG ?>';
        let cart = {}; // Cấu trúc: { product_id: { product, quantity } }

        // ==================== RENDER DANH MỤC & SẢN PHẨM ====================
        function renderCategories() {
            const wrapper = document.getElementById('categoryWrapper');
            const categories = [...new Set(products.map(p => p.category_name))];
            
            let html = `<div class="cat-btn active" onclick="filterCategory('all', this)">Tất cả</div>`;
            categories.forEach(cat => { html += `<div class="cat-btn" onclick="filterCategory('${cat}', this)">${cat}</div>`; });
            wrapper.innerHTML = html;
        }

        function renderProducts(filteredProducts) {
            const list = document.getElementById('productList');
            let html = '';
            
            if(filteredProducts.length === 0) {
                list.innerHTML = `<div class="text-center py-5 text-muted">Không tìm thấy món ăn.</div>`;
                return;
            }

            filteredProducts.forEach(p => {
                const qty = cart[p.id] ? cart[p.id].quantity : 0;
                html += `
                <div class="product-item">
                    <img src="${assetImg}${p.image}" class="product-img" onerror="this.src='${assetImg}default.jpg'">
                    <div class="product-info">
                        <div>
                            <h3 class="product-name">${p.name}</h3>
                            <small class="text-muted">${p.category_name}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="product-price">${Number(p.price).toLocaleString('vi-VN')} đ</div>
                            
                            ${qty === 0 ? `
                                <button class="add-btn" onclick="updateCart(${p.id}, 1)">Thêm <i class="fas fa-plus ms-1"></i></button>
                            ` : `
                                <div class="qty-control">
                                    <button class="qty-btn" onclick="updateCart(${p.id}, -1)">-</button>
                                    <input type="text" class="qty-input" value="${qty}" readonly>
                                    <button class="qty-btn" onclick="updateCart(${p.id}, 1)">+</button>
                                </div>
                            `}
                        </div>
                    </div>
                </div>`;
            });
            list.innerHTML = html;
        }

        function filterCategory(cat, el) {
            document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
            renderProducts(cat === 'all' ? products : products.filter(p => p.category_name === cat));
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            renderProducts(products.filter(p => p.name.toLowerCase().includes(term)));
        });

        // ==================== XỬ LÝ GIỎ HÀNG ====================
        function updateCart(id, change) {
            if (!cart[id]) {
                const product = products.find(p => p.id == id);
                cart[id] = { product: product, quantity: 0 };
            }
            
            cart[id].quantity += change;
            
            if (cart[id].quantity <= 0) {
                delete cart[id];
            } else if (change > 0) {
                showToast();
            }

            updateCartUI();
            
            const activeCat = document.querySelector('.cat-btn.active').innerText;
            const term = document.getElementById('searchInput').value.toLowerCase();
            let filtered = products;
            if(activeCat !== 'Tất cả') filtered = filtered.filter(p => p.category_name === activeCat);
            if(term) filtered = filtered.filter(p => p.name.toLowerCase().includes(term));
            renderProducts(filtered);
        }

        function updateCartUI() {
            let totalItems = 0;
            let totalPrice = 0;
            
            for (let id in cart) {
                totalItems += cart[id].quantity;
                totalPrice += cart[id].quantity * cart[id].product.price;
            }
            
            document.getElementById('cartCount').innerText = totalItems;
            document.getElementById('cartTotal').innerText = totalPrice.toLocaleString('vi-VN') + ' đ';
            document.getElementById('modalTotal').innerText = totalPrice.toLocaleString('vi-VN') + ' đ';
            
            const bottomBar = document.querySelector('.bottom-bar');
            bottomBar.style.transform = totalItems > 0 ? 'translateY(0)' : 'translateY(150%)';
            bottomBar.style.transition = 'transform 0.3s ease';
            
            renderCartModal();
        }

        const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
        
        function openCart() {
            if (Object.keys(cart).length === 0) return;
            cartOffcanvas.show();
        }

        function renderCartModal() {
            const container = document.getElementById('cartItemsContainer');
            if (Object.keys(cart).length === 0) {
                container.innerHTML = `<div class="text-center py-4">Giỏ hàng trống</div>`;
                cartOffcanvas.hide();
                return;
            }

            let html = '';
            for (let id in cart) {
                const item = cart[id];
                html += `
                <div class="bg-white p-3 rounded-3 shadow-sm mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-dark">${item.product.name}</div>
                        <div class="text-primary fw-semibold">${Number(item.product.price).toLocaleString('vi-VN')} đ</div>
                    </div>
                    <div class="qty-control">
                        <button class="qty-btn" onclick="updateCart(${id}, -1)">-</button>
                        <input type="text" class="qty-input" value="${item.quantity}" readonly>
                        <button class="qty-btn" onclick="updateCart(${id}, 1)">+</button>
                    </div>
                </div>`;
            }
            container.innerHTML = html;
        }

        // ==================== AJAX GỬI ORDER & POPUP ANIMATION ====================
        function submitOrder() {
            if (Object.keys(cart).length === 0) return;
            
            const items = Object.values(cart).map(item => ({
                product_id: item.product.id,
                quantity: item.quantity,
                unit_price: item.product.price
            }));

            fetch('<?= URLROOT ?>/order/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table_id: <?= $table['id'] ?? 0 ?>,
                    staff_id: null,
                    items: items
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartOffcanvas.hide();
                    
                    // Gọi hàm hiển thị Popup Animation (Đã fix: bỏ truyền order_id)
                    showSuccessPopup();
                    
                    cart = {}; // Xóa giỏ hàng
                    updateCartUI();
                    renderProducts(products); // Reset lại UI
                } else {
                    alert('❌ Lỗi: ' + data.message);
                }
            })
            .catch(() => alert('Lỗi kết nối đến máy chủ!'));
        }

        // [ĐÃ FIX] Hàm kích hoạt Popup (bỏ orderId)
        function showSuccessPopup() {
            const overlay = document.getElementById('successOverlay');
            const modal = document.getElementById('successModal');
            
            overlay.style.display = 'flex';
            // Mẹo ép trình duyệt render lại (reflow) để animation chạy mượt
            void overlay.offsetWidth; 
            modal.classList.add('active');
        }

        // Hàm đóng Popup
        function closeSuccessPopup() {
            const overlay = document.getElementById('successOverlay');
            const modal = document.getElementById('successModal');
            
            modal.classList.remove('active');
            
            // Đợi 300ms cho animation mờ dần kết thúc rồi mới ẩn hoàn toàn div
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }

        // ==================== CÁC HÀM PHỤ ====================
        function showToast() {
            const toast = document.getElementById('toastMessage');
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 1000);
        }

        function callStaff() {
            alert('Đã gửi thông báo gọi nhân viên hỗ trợ / tính tiền cho bàn của bạn!');
        }

        window.onload = () => {
            renderCategories();
            renderProducts(products);
            updateCartUI(); 
        };
    </script>
</body>
</html>