<?php require_once '../app/views/inc/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-users text-primary me-2"></i> Quản lý Nhân sự</h2>
        <button class="btn btn-primary fw-bold shadow-sm" onclick="openStaffModal()">
            <i class="fas fa-plus me-1"></i> Thêm Nhân Viên
        </button>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Họ và Tên</th>
                            <th>Tên đăng nhập</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['staffs'] as $user): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $user['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td>
                                <?php if($user['role'] === 'admin'): ?>
                                    <span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i>Quản trị (Admin)</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark"><i class="fas fa-user me-1"></i>Nhân viên (Staff)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user['status'] === 'active'): ?>
                                    <span class="badge bg-success">Đang hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Bị khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-warning me-1 shadow-sm" 
                                    onclick="openStaffModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>', '<?= htmlspecialchars($user['full_name']) ?>', '<?= $user['role'] ?>', '<?= $user['status'] ?>')">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <a href="<?= URLROOT ?>/staff/delete/<?= $user['id'] ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= URLROOT ?>/staff/save" method="POST">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalTitle">Thêm Nhân Viên Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="staff_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và Tên</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required placeholder="Nhập tên nhân viên...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập (Tài khoản)</label>
                        <input type="text" id="username" name="username" class="form-control" required placeholder="Ví dụ: nhanvien01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu...">
                        <small class="text-danger" id="passwordHint" style="display:none;">* Bỏ trống nếu không muốn đổi mật khẩu cũ</small>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Vai trò</label>
                            <select id="role" name="role" class="form-select">
                                <option value="staff">Nhân viên phục vụ</option>
                                <option value="admin">Quản trị viên (Admin)</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Khóa tài khoản</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-save me-1"></i> Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hàm mở Modal dùng chung cho cả Thêm mới và Chỉnh sửa
    function openStaffModal(id = '', username = '', fullName = '', role = 'staff', status = 'active') {
        document.getElementById('staff_id').value = id;
        document.getElementById('username').value = username;
        document.getElementById('full_name').value = fullName;
        document.getElementById('role').value = role;
        document.getElementById('status').value = status;
        
        // Nếu là Thêm mới (id rỗng) -> Bắt buộc nhập mật khẩu
        if (!id) {
            document.getElementById('modalTitle').innerText = 'Thêm Nhân Viên Mới';
            document.getElementById('password').required = true;
            document.getElementById('passwordHint').style.display = 'none';
        } else {
            // Nếu là Sửa (có id) -> Không bắt buộc nhập mật khẩu (nhập thì update, không thì giữ nguyên)
            document.getElementById('modalTitle').innerText = 'Chỉnh sửa Thông tin';
            document.getElementById('password').required = false;
            document.getElementById('password').value = '';
            document.getElementById('passwordHint').style.display = 'block';
        }
        
        new bootstrap.Modal(document.getElementById('staffModal')).show();
    }
</script>

<?php require_once '../app/views/inc/footer.php'; ?>