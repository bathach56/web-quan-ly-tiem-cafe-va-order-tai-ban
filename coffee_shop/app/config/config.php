<?php
// Thiết lập múi giờ chuẩn cho Việt Nam (Quan trọng cho việc lưu created_at trong Database)
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ==========================================
// 1. THÔNG SỐ KẾT NỐI CƠ SỞ DỮ LIỆU
// ==========================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Mặc định của XAMPP/WAMP
define('DB_PASS', '');          // Mặc định của XAMPP thường là rỗng
define('DB_NAME', 'coffee_shop');

// ==========================================
// 2. ĐƯỜNG DẪN HỆ THỐNG TƯƠNG ĐỐI (Dành cho require/include file trong PHP)
// ==========================================
// dirname(__FILE__) đang ở /app/config -> dirname lần nữa sẽ ra thư mục /app
define('APPROOT', dirname(dirname(__FILE__)));

// ==========================================
// 3. ĐƯỜNG DẪN URL TUYỆT ĐỐI (Dành cho file tĩnh CSS, JS, Ảnh và link href, form action)
// ==========================================
// ĐỔI TÊN 'coffee_shop' THÀNH TÊN THƯ MỤC THỰC TẾ CỦA BẠN NẾU KHÁC
define('URLROOT', 'http://localhost:8080/coffee_shop');

// ==========================================
// 4. CẤU HÌNH THÔNG TIN CHUNG
// ==========================================
define('SITENAME', 'Hệ Thống Quản Lý Coffee Shop');
define('APP_VERSION', '1.0.0');

// Đường dẫn nhanh tới thư mục chứa ảnh sản phẩm
define('ASSET_IMG', URLROOT . '/public/img/');
?>