    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript cho giỏ hàng (sẽ dùng ở menu.php) -->
    <script>
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
            
            // Toast thông báo
            const toastHTML = `
                <div class="toast align-items-center bg-success text-white" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${name}</strong> đã thêm vào giỏ!
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
            }
        }
        
        // Gửi giỏ hàng khi checkout (sẽ gọi AJAX đến Order/create)
        function checkout() {
            if (cart.length === 0) return alert('Giỏ hàng trống!');
            
            const tableId = <?= $table['id'] ?? 0 ?>;
            
            fetch('<?= URLROOT ?>/order/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table_id: tableId,
                    staff_id: null,           // Khách tự order
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
                    alert('✅ Đơn hàng #' + data.order_id + ' đã được tạo thành công!\nCảm ơn bạn đã order.');
                    cart = [];
                    updateCartUI();
                    // Có thể redirect hoặc reload trang
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(() => alert('Lỗi kết nối server'));
        }
    </script>
</body>
</html>