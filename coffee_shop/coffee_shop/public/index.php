<?php
// ==========================================
// 1. CẤU HÌNH BẢO MẬT & SESSION
// ==========================================
// Ép Session tự hủy khi trình duyệt đóng hoàn toàn
ini_set('session.cookie_lifetime', 0);
session_set_cookie_params(0);

// Khởi động Session
session_start();

// ==========================================
// 2. LOAD CẤU HÌNH HỆ THỐNG
// ==========================================
// Load config trước để sử dụng biến hằng số URLROOT cho việc chuyển hướng
require_once '../app/config/config.php';

// ==========================================
// 3. BỘ ĐẾM BẢO MẬT: HEARTBEAT (NHỊP TIM)
// ==========================================
$tab_timeout = 15; // Giới hạn 15 giây không nhận được tín hiệu Ping là đăng xuất

// Nếu đã đăng nhập và có lưu mốc thời gian Ping cuối cùng
if (isset($_SESSION['user_id']) && isset($_SESSION['last_ping'])) {
    // Nếu thời gian hiện tại cách lần Ping cuối quá 15 giây (nghĩa là Tab đã bị đóng)
    if (time() - $_SESSION['last_ping'] > $tab_timeout) {
        
        // Hủy toàn bộ phiên làm việc cũ
        session_unset();
        session_destroy();
        
        // Khởi tạo lại một session mới tĩnh để chứa thông báo lỗi
        session_start(); 
        $_SESSION['login_error'] = "Đã đăng xuất do đóng cửa sổ làm việc hoặc mất kết nối!";
        
        // Dùng JS để chuyển hướng an toàn, tránh lỗi "Headers already sent" của PHP
        echo '<script>window.location.href="' . URLROOT . '/auth/admin";</script>';
        exit;
    }
}

// Cập nhật lại mốc Ping mỗi khi người dùng thao tác load/chuyển trang bình thường (F5)
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_ping'] = time(); 
}

// ==========================================
// 4. KHỞI TẠO CÁC CORE CLASS
// ==========================================
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Khởi chạy hệ thống định tuyến (Router)
$app = new App();
?>