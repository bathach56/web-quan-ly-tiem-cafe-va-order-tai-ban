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
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $table_id = $_POST['table_id'] ?? null;
            $staff_id = $_POST['staff_id'] ?? null;
            $items    = $_POST['items'] ?? [];
            $status   = $_POST['status'] ?? 'pending';

            // Xử lý dữ liệu JSON gửi lên từ Fetch API
            if (empty($items) && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $input = json_decode(file_get_contents('php://input'), true);
                $table_id = $input['table_id'] ?? null;
                $staff_id = $input['staff_id'] ?? null;
                $items    = $input['items'] ?? [];
                $status   = $input['status'] ?? 'pending'; // Nhận thêm status (pending, processing, completed)
            }

            if (!$table_id || empty($items)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu (Bàn hoặc Món ăn)!']);
                exit;
            }

            // Gọi Model để xử lý Transaction lưu Database
            $orderId = $this->orderModel->createOrder($table_id, $staff_id, $items, $status);

            if ($orderId) {
                echo json_encode([
                    'success' => true,
                    'order_id' => $orderId,
                    'message' => 'Thao tác đơn hàng thành công!'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi lưu đơn hàng!']);
            }
        }
        exit;
    }
}
?>