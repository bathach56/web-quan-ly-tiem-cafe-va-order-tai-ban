<?php
class Inventory extends Controller {
    private $inventoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . URLROOT . "/dashboard");
            exit;
        }
        $this->inventoryModel = $this->model('InventoryModel');
    }

    public function index() {
        $items = $this->inventoryModel->getInventoryStatus();
        $data = [
            'title' => 'Báo Cáo Tồn Kho',
            'items' => $items
        ];
        $this->view('admin/inventory', $data);
    }

    // Xử lý tạo nguyên liệu mới
    public function createItem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $unit = trim($_POST['unit']);
            $min_stock = (int)$_POST['min_stock'];

            $this->inventoryModel->addItem($name, $unit, $min_stock);
            $_SESSION['flash'] = "✅ Thêm danh mục nguyên liệu thành công!";
            header("Location: " . URLROOT . "/inventory");
            exit;
        }
    }

    // Xử lý tạo phiếu nhập/xuất
    public function transaction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = (int)$_POST['item_id'];
            $type = $_POST['type']; // 'in' hoặc 'out'
            $quantity = (int)$_POST['quantity'];
            $note = trim($_POST['note']);
            $staff_id = $_SESSION['user_id'];

            // Nếu xuất kho, cần kiểm tra xem có đủ hàng không (Tùy chọn)
            $this->inventoryModel->addTransaction($item_id, $type, $quantity, $note, $staff_id);
            
            $msg = $type == 'in' ? "Nhập kho" : "Xuất kho";
            $_SESSION['flash'] = "✅ Đã tạo phiếu $msg thành công!";
            header("Location: " . URLROOT . "/inventory");
            exit;
        }
    }
}
?>