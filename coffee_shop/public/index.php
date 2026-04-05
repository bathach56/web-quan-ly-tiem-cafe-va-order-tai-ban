<?php
// 1. Cấu hình ép buộc Session tự hủy khi đóng trình duyệt
ini_set('session.cookie_lifetime', 0);
session_set_cookie_params(0);

// 2. Bắt đầu Session
session_start();

// Các đoạn require_once cấu hình và khởi tạo App bên dưới giữ nguyên...
require_once '../app/config/config.php';
// ...

// BẬT HIỂN THỊ LỖI (Dùng trong quá trình Dev, khi deploy lên host thật thì đổi thành 0)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Load file cấu hình hệ thống
require_once '../app/config/config.php';

// 2. Autoload các file Core của hệ thống (App, Controller, Database)
// Hàm này sẽ tự động require file khi bạn khởi tạo class mới bằng từ khóa 'new'
spl_autoload_register(function($className) {
    $file = '../app/core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("❌ Lỗi hệ thống (Core Autoload): Không tìm thấy file <b>{$className}.php</b> trong thư mục app/core/");
    }
});

// 3. Khởi tạo ứng dụng (Chạy Router)
// Quá trình này sẽ lấy URL, tìm đúng Controller và gọi Method tương ứng
$init = new App();
?>