<?php
class Auth extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    // Trang đăng nhập Admin
    public function admin() {
        // Nếu đã đăng nhập rồi mà lỡ truy cập lại trang login thì đá sang trang product luôn
        if (isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/product");
            exit;
        }

        $data = ['title' => 'Đăng nhập Admin - Coffee Shop'];
        $this->view('auth/admin_login', $data);
    }

    // Xử lý đăng nhập Admin
    public function admin_authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($username, $password);

            // Chấp nhận cả mật khẩu đã hash HOẶC mật khẩu thô (nếu bạn chưa hash DB)
            if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                // ĐÃ ĐỔI ĐÍCH ĐẾN TỪ /dashboard SANG /product 
                header("Location: " . URLROOT . "/product");
                exit;
            } else {
                $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                header("Location: " . URLROOT . "/auth/admin");
                exit;
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        header("Location: " . URLROOT . "/auth/admin");
        exit;
    }
}
?>