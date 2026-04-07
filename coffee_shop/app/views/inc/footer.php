</div> </div> </div> <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index: 1100;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // ----------------------------------------
    // 1. KHỞI TẠO HIỆU ỨNG VÀ GIAO DIỆN CHUNG
    // ----------------------------------------
    AOS.init({
        duration: 800,
        once: true,
        offset: 30
    });

    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('sidebarCollapse');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });
        }
    });

    // ----------------------------------------
    // 2. NHỊP TIM (HEARTBEAT) BẢO MẬT ADMIN
    // ----------------------------------------
    <?php if(isset($_SESSION['user_id'])): ?>
    setInterval(function() {
        fetch('<?= URLROOT ?>/auth/ping').catch(() => {});
    }, 10000);
    <?php endif; ?>

    // ----------------------------------------
    // 3. XỬ LÝ GIỎ HÀNG CHO KHÁCH HÀNG (MENU.PHP)
    // ----------------------------------------
    let cart = [];
    
    function addToCart(productId, name, price, image) {
        const existing = cart.find(item => item.id === productId);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({
                id: productId,
                name: name,
                price: parseFloat(price),
                image: image,
                quantity: 1
            });
        }
        updateCartUI();
        
        // Hiển thị thông báo góc màn hình
        const toastHTML = `
            <div class="toast align-items-center bg-success text-white shadow-lg border-0" role="alert" style="border-radius: 10px;">
                <div class="d-flex">
                    <div class="toast-body fw-bold fs-6">
                        <i class="fas fa-check-circle me-2"></i> Đã thêm <strong>${name}</strong> vào giỏ!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
            
        const toastContainer = document.getElementById('toastContainer');
        if (toastContainer) {
            toastContainer.innerHTML = toastHTML;
            const toast = new bootstrap.Toast(toastContainer.firstElementChild);
            toast.show();
        }
    }
    
    function updateCartUI() {
        const countEl = document.getElementById('cartCount');
        if (countEl) {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            countEl.textContent = totalItems;
            
            // Hiệu ứng nảy số lượng
            countEl.style.transform = "scale(1.3)";
            setTimeout(() => countEl.style.transform = "scale(1)", 200);
        }
    }
    
    function checkout() {
        if (cart.length === 0) return alert('Giỏ hàng của bạn đang trống! Vui lòng chọn món.');
        
        // Lấy Table ID tùy theo biến truyền vào từ View
        const tableId = <?= isset($data['table']['id']) ? $data['table']['id'] : (isset($table['id']) ? $table['id'] : 0) ?>;
        
        if (tableId === 0) {
            return alert('Lỗi: Không xác định được số bàn!');
        }

        if (!confirm('Bạn có chắc chắn muốn gửi Order này xuống bếp?')) return;

        const btn = document.getElementById('btnCheckout');
        if(btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang gửi...'; }
        
        fetch('<?= URLROOT ?>/order/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                table_id: tableId,
                staff_id: null,
                status: 'pending', 
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    unit_price: item.price
                }))
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ Gửi Order thành công!\nĐơn hàng của bạn đang được bếp chuẩn bị.');
                cart = [];
                location.reload(); 
            } else {
                alert('❌ ' + data.message);
                if(btn) { btn.disabled = false; btn.innerHTML = 'Gửi Order'; }
            }
        })
        .catch(() => {
            alert('Lỗi kết nối đến máy chủ!');
            if(btn) { btn.disabled = false; btn.innerHTML = 'Gửi Order'; }
        });
    }
</script>

</body>
</html>