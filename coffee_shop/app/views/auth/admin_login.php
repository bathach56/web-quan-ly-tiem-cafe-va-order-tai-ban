<?php require_once '../app/views/inc/header.php'; ?>

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light" style="background: linear-gradient(135deg, #8d5524 0%, #5c3311 100%);">
    <div class="row justify-content-center w-100">
        <div class="col-lg-4 col-md-6 col-sm-8">
            
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-dark text-white text-center py-4">
                    <h3 class="mb-1 fw-bold">
                        <i class="fas fa-coffee me-2"></i> Coffee Shop
                    </h3>
                    <p class="mb-0 opacity-75">Quản trị hệ thống</p>
                </div>

                <div class="card-body p-5">
                    <h4 class="text-center mb-4 text-dark">Đăng nhập Admin</h4>

                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= $_SESSION['login_error'] ?>
                        </div>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>

                    <form action="<?= URLROOT ?>/auth/admin_authenticate" method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control form-control-lg" 
                                       placeholder="Nhập tên đăng nhập" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Ghi nhớ tôi</label>
                            </div>
                            <a href="#" onclick="forgotPassword()" class="text-decoration-none text-primary">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold text-dark">
                            <i class="fas fa-sign-in-alt me-2"></i> ĐĂNG NHẬP
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        Chỉ dành cho Quản trị viên hệ thống
                    </small>
                </div>
            </div>

            <!-- Back to POS -->
            <div class="text-center mt-4">
                <a href="<?= URLROOT ?>/order/pos" class="text-white opacity-75 text-decoration-none">
                    ← Quay lại màn hình POS
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function forgotPassword() {
    const username = prompt("Nhập tên đăng nhập của bạn:");
    if (username) {
        alert("🔄 Tính năng khôi phục mật khẩu đang được phát triển.\n\nVui lòng liên hệ quản lý để reset mật khẩu.");
    }
}
</script>

<?php require_once '../app/views/inc/footer.php'; ?>