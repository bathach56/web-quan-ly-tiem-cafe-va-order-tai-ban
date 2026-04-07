<?php
class Staff extends Controller {
    private $userModel;

    public function __construct() {
        // 1. Kiểm tra đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/auth/admin");
            exit;
        }
        // 2. PHÂN QUYỀN: Chỉ có Admin mới được vào trang này
        if ($_SESSION['role'] !== 'admin') {
            header("Location: " . URLROOT . "/dashboard");
            exit;
        }

        $this->userModel = $this->model('UserModel');
    }

    // Trang hiển thị danh sách
    public function index() {
        $staffs = $this->userModel->getAllStaff();
        $data = [
            'title' => 'Quản lý Nhân Sự',
            'staffs' => $staffs
        ];
        $this->view('admin/staff', $data);
    }

    // Xử lý Thêm / Sửa nhân viên (Lưu từ Form)
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $data = [
                'username'  => trim($_POST['username']),
                'full_name' => trim($_POST['full_name']),
                'role'      => $_POST['role'],
                'status'    => $_POST['status'] ?? 'active',
                'password'  => $_POST['password'] // Có thể rỗng nếu đang update
            ];

            if ($id) {
                $this->userModel->update($id, $data);
                $_SESSION['flash'] = "✅ Cập nhật nhân sự thành công!";
            } else {
                $this->userModel->create($data);
                $_SESSION['flash'] = "✅ Thêm nhân sự mới thành công!";
            }
            header("Location: " . URLROOT . "/staff");
            exit;
        }
    }

    // Xóa nhân viên
    public function delete($id) {
        // Ngăn Admin tự xóa chính tài khoản của mình
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash'] = "❌ Lỗi: Bạn không thể tự xóa tài khoản đang đăng nhập!";
        } else {
            $this->userModel->delete($id);
            $_SESSION['flash'] = "✅ Xóa nhân viên thành công!";
        }
        header("Location: " . URLROOT . "/staff");
        exit;
    }
}
?>