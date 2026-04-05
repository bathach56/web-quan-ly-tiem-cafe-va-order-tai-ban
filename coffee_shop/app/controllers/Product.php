<?php
class Product extends Controller {

    private $productModel;

    public function __construct() {
        // ========================================================
        // 🛡️ BỨC TƯỜNG BẢO VỆ (AUTH GUARD)
        // Kiểm tra đã đăng nhập chưa, chưa thì đẩy ra trang Login
        // ========================================================
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . URLROOT . "/auth/admin");
            exit;
        }

        $this->productModel = $this->model('ProductModel');
    }

    // Trang danh sách quản lý món (dành cho nhân viên / admin)
    public function index() {
        $products = $this->productModel->getAllWithCategory();
        $categories = $this->getCategories();

        $data = [
            'products'   => $products,
            'categories' => $categories,
            'title'      => 'Quản lý Món Ăn'
        ];

        $this->view('staff/products', $data);   // Dùng view riêng cho staff
    }

    // Xử lý thêm / sửa món
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $category_id = (int)$_POST['category_id'];
            $name        = trim($_POST['name']);
            $price       = (float)$_POST['price'];
            $status      = $_POST['status'] ?? 'active';

            $image_name = $this->uploadImage();

            $data = [
                'category_id' => $category_id,
                'name'        => $name,
                'price'       => $price,
                'status'      => $status
            ];

            // Chỉ cập nhật ảnh nếu người dùng có chọn ảnh mới
            if ($image_name) {
                $data['image'] = $image_name;
            }

            if ($id) {
                $result = $this->productModel->update($id, $data);
                $_SESSION['flash'] = $result ? "✅ Cập nhật món thành công!" : "❌ Cập nhật thất bại!";
            } else {
                $result = $this->productModel->create($data);
                $_SESSION['flash'] = $result ? "✅ Thêm món mới thành công!" : "❌ Thêm món thất bại!";
            }

            header("Location: " . URLROOT . "/product");
            exit;
        }
    }

    // Upload ảnh
    private function uploadImage() {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
            return null;
        }

        $upload_dir = dirname(dirname(__DIR__)) . '/public/img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_name = 'product_' . time() . rand(100, 999) . '.' . $ext;
        $target_file = $upload_dir . $new_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            return $new_name;
        }
        return null;
    }

    // Lấy danh sách danh mục (Làm phẳng, không cần CategoryModel)
    private function getCategories() {
        $db = new Database();
        $db->query("SELECT * FROM categories ORDER BY name ASC");
        return $db->resultSet();
    }

    // Xóa món
    public function delete($id) {
        if ($this->productModel->delete($id)) {
            $_SESSION['flash'] = "✅ Xóa món thành công!";
        } else {
            $_SESSION['flash'] = "❌ Không thể xóa món này do đang có đơn hàng liên quan!";
        }
        header("Location: " . URLROOT . "/product");
        exit;
    }
}
?>