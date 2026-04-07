<?php
class Auth extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    // ĐÂY LÀ HÀM SẼ KHẮC PHỤC LỖI BẠN ĐANG GẶP
    public function index() {
        header("Location: " . URLROOT . "/auth/admin");
        exit;
    }

    // Trang đăng nhập
    public function admin() {
        if (isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/dashboard");
            exit;
        }
        $data = ['title' => 'Đăng nhập Admin'];
        $this->view('auth/admin_login', $data);
    }

    public function login() {
        $this->admin_authenticate();
    }

    // Xử lý đăng nhập
    public function admin_authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($username, $password);

            if ($user === 'locked') {
                $_SESSION['login_error'] = "Tài khoản bị khóa!";
                header("Location: " . URLROOT . "/auth/admin");
                exit;
            } elseif ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['last_ping'] = time(); 

                header("Location: " . URLROOT . "/dashboard");
                exit;
            } else {
                $_SESSION['login_error'] = "Sai tài khoản hoặc mật khẩu!";
                header("Location: " . URLROOT . "/auth/admin");
                exit;
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . URLROOT . "/auth/admin");
        exit;
    }

    // Giữ Session
    public function ping() {
        if (isset($_SESSION['user_id'])) {
            $_SESSION['last_ping'] = time();
            echo json_encode(['status' => 'alive']);
        } else {
            echo json_encode(['status' => 'dead']);
        }
        exit;
    }
}
?>