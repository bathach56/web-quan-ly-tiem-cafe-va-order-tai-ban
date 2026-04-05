<?php
class Order extends Controller {

    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->orderModel   = $this->model('OrderModel');
    }

    /**
     * ==========================================
     * PHÂN HỆ KHÁCH HÀNG - Menu Order tại bàn
     * URL: /order/menu/{table_id}
     * ==========================================
     */
    public function menu($table_id = null) {
        if (!$table_id || !is_numeric($table_id)) {
            die("Lỗi: Table ID không hợp lệ!");
        }

        // Lấy thông tin bàn
        $db = new Database();
        $db->query("SELECT id, table_name, status FROM tables WHERE id = :id");
        $db->bind(':id', $table_id);
        $table = $db->single();

        if (!$table) {
            die("Lỗi: Bàn không tồn tại!");
        }

        $products = $this->productModel->getAll();

        $data = [
            'table'     => $table,
            'products'  => $products,
            'title'     => 'Menu - Bàn ' . $table['table_name']
        ];

        $this->view('customer/menu', $data);
    }

    /**
     * ==========================================
     * PHÂN HỆ NHÂN VIÊN - Màn hình POS Thu Ngân
     * URL: /order/pos
     * ==========================================
     */
    public function pos() {
        $products = $this->productModel->getAll();

        $db = new Database();
        $db->query("SELECT id, table_name, status FROM tables ORDER BY table_name ASC");
        $tables = $db->resultSet();

        $data = [
            'products' => $products,
            'tables'   => $tables,
            'title'    => 'POS - Thu Ngân & Phục Vụ'
        ];

        $this->view('staff/pos', $data);
    }

    /**
     * ==========================================
     * API: Lấy thông tin đơn hàng đang active của 1 bàn
     * URL: /order/get_active_order/{table_id}
     * ==========================================
     */
    public function get_active_order($table_id) {
        $order = $this->orderModel->getActiveOrder($table_id);
        
        if ($order) {
            $details = $this->orderModel->getOrderDetails($order['id']);
            echo json_encode(['success' => true, 'order' => $order, 'details' => $details]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    /**
     * ==========================================
     * API: Tạo / Cập nhật / Tính tiền đơn hàng (AJAX)
     * URL: /order/create
     * ==========================================
     */
    // API tạo đơn hàng (Nhận dữ liệu JSON từ frontend)
    // API tạo đơn hàng (Nhận dữ liệu JSON từ frontend)
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Đọc dữ liệu JSON gửi từ Fetch API
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['table_id']) || empty($data['items'])) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $table_id = $data['table_id'];
            $staff_id = $data['staff_id'] ?? null;
            $items    = $data['items'];
            
            // LẤY BIẾN STATUS TỪ JSON (Nếu không có thì mặc định là 'pending')
            $status   = $data['status'] ?? 'pending';

            // TRUYỀN BIẾN $status VÀO LÀM THAM SỐ THỨ 4 CHO MODEL
            $order_id = $this->orderModel->createOrder($table_id, $staff_id, $items, $status);

            if ($order_id) {
                echo json_encode(['success' => true, 'order_id' => $order_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi tạo đơn hàng']);
            }
            exit;
        }
    }
}

?>