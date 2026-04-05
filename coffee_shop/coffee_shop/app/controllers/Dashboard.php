<?php
class Dashboard extends Controller {

    public function __construct() {
        // ========================================================
        // 🛡️ BỨC TƯỜNG BẢO VỆ (AUTH GUARD)
        // Kiểm tra đăng nhập, chưa đăng nhập thì đá về trang Login
        // ========================================================
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/auth/admin");
            exit;
        }
    }

    public function index() {
        // ========================================================
        // 🚦 PHÂN QUYỀN THÔNG MINH (AUTHORIZATION)
        // Nếu là Nhân viên (staff) -> Chuyển thẳng ra giao diện POS
        // ========================================================
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') {
            header("Location: " . URLROOT . "/order/pos");
            exit;
        }

        // ========================================================
        // 📊 LẤY DỮ LIỆU THỐNG KÊ CHO ADMIN
        // ========================================================
        $db = new Database();

        // Thống kê cơ bản
        $db->query("SELECT COUNT(*) as total_products FROM products");
        $total_products = $db->single()['total_products'];

        $db->query("SELECT COUNT(*) as total_orders FROM orders");
        $total_orders = $db->single()['total_orders'];

        $db->query("SELECT COUNT(*) as total_tables FROM tables");
        $total_tables = $db->single()['total_tables'];

        $db->query("SELECT COUNT(*) as occupied_tables FROM tables WHERE status = 'occupied'");
        $occupied_tables = $db->single()['occupied_tables'];

        // Doanh thu hôm nay (Chỉ tính các đơn đã hoàn tất - completed)
        $db->query("SELECT COALESCE(SUM(total_amount), 0) as today_revenue 
                    FROM orders 
                    WHERE DATE(created_at) = CURDATE() AND status = 'completed'");
        $today_revenue = $db->single()['today_revenue'];

        // Đổ dữ liệu ra View
        $data = [
            'title'              => 'Dashboard Admin',
            'total_products'     => $total_products,
            'total_orders'       => $total_orders,
            'total_tables'       => $total_tables,
            'occupied_tables'    => $occupied_tables,
            'today_revenue'      => $today_revenue,
            'full_name'          => $_SESSION['full_name'] ?? 'Admin'
        ];

        $this->view('admin/dashboard', $data);
    }
}
?>