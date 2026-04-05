<?php
class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Tạo đơn hàng mới / Cập nhật đơn / Tính tiền
     * Sử dụng TRANSACTION để đảm bảo toàn vẹn dữ liệu
     */
    public function createOrder($table_id, $staff_id = null, $items = [], $status = 'pending') {
        try {
            // Bắt đầu Transaction
            $this->db->query('BEGIN');
            
            // Tìm xem bàn này đang có Order nào chưa thanh toán không
            $this->db->query("SELECT id, total_amount FROM orders WHERE table_id = :table_id AND status IN ('pending', 'processing') LIMIT 1");
            $this->db->bind(':table_id', $table_id);
            $activeOrder = $this->db->single();

            // =========================================================================
            // LUỒNG 1: KHÁCH HÀNG QUÉT MÃ GỌI THÊM MÓN (CỘNG DỒN VÀO ORDER HIỆN TẠI)
            // =========================================================================
            if ($staff_id === null && $activeOrder) {
                
                $order_id = $activeOrder['id'];
                $total_amount = $activeOrder['total_amount'];

                foreach ($items as $item) {
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $total_amount += $subtotal;

                    // Kiểm tra xem món khách gọi thêm ĐÃ CÓ trong order cũ chưa?
                    $this->db->query("SELECT id FROM order_details WHERE order_id = :order_id AND product_id = :product_id");
                    $this->db->bind(':order_id', $order_id);
                    $this->db->bind(':product_id', $item['product_id']);
                    $existDetail = $this->db->single();

                    if ($existDetail) {
                        // Nếu món đã có -> Chỉ cần cộng thêm số lượng
                        $this->db->query('UPDATE order_details SET quantity = quantity + :qty WHERE id = :id');
                        $this->db->bind(':qty', $item['quantity']);
                        $this->db->bind(':id', $existDetail['id']);
                        $this->db->execute();
                    } else {
                        // Nếu là món mới hoàn toàn -> Insert dòng mới
                        $this->db->query('INSERT INTO order_details (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)');
                        $this->db->bind(':order_id', $order_id);
                        $this->db->bind(':product_id', $item['product_id']);
                        $this->db->bind(':quantity', $item['quantity']);
                        $this->db->bind(':unit_price', $item['unit_price']);
                        $this->db->execute();
                    }
                }

                // Cập nhật lại tổng tiền cho Order
                $this->db->query('UPDATE orders SET total_amount = :total, status = :status WHERE id = :order_id');
                $this->db->bind(':total', $total_amount);
                $this->db->bind(':status', $status);
                $this->db->bind(':order_id', $order_id);
                $this->db->execute();

            } 
            // =========================================================================
            // LUỒNG 2: NHÂN VIÊN POS THAO TÁC (GHI ĐÈ) HOẶC BÀN MỚI TINH KHÁCH VỪA VÀO
            // =========================================================================
            else {
                
                // Nếu nhân viên lưu/tính tiền -> Xóa chi tiết cũ để ghi đè danh sách mới từ POS
                if ($activeOrder) {
                    $this->db->query("DELETE FROM order_details WHERE order_id = :order_id");
                    $this->db->bind(':order_id', $activeOrder['id']);
                    $this->db->execute();
                    
                    $this->db->query("DELETE FROM orders WHERE id = :order_id");
                    $this->db->bind(':order_id', $activeOrder['id']);
                    $this->db->execute();
                }

                // Tạo Order mới
                $this->db->query('INSERT INTO orders (table_id, staff_id, total_amount, status) VALUES (:table_id, :staff_id, 0, :status)');
                $this->db->bind(':table_id', $table_id);
                $this->db->bind(':staff_id', $staff_id);
                $this->db->bind(':status', $status);
                $this->db->execute();
                
                $order_id = $this->db->lastInsertId(); // Lấy ID qua hàm public ở class Database
                $total_amount = 0;

                foreach ($items as $item) {
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $total_amount += $subtotal;

                    $this->db->query('INSERT INTO order_details (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)');
                    $this->db->bind(':order_id', $order_id);
                    $this->db->bind(':product_id', $item['product_id']);
                    $this->db->bind(':quantity', $item['quantity']);
                    $this->db->bind(':unit_price', $item['unit_price']);
                    $this->db->execute();
                }

                $this->db->query('UPDATE orders SET total_amount = :total WHERE id = :order_id');
                $this->db->bind(':total', $total_amount);
                $this->db->bind(':order_id', $order_id);
                $this->db->execute();
            }

            // =========================================================================
            // 3. CẬP NHẬT TRẠNG THÁI BÀN (DÙNG CHUNG)
            // =========================================================================
            $tableStatus = ($status === 'completed') ? 'empty' : 'occupied';
            $this->db->query('UPDATE tables SET status = :status WHERE id = :table_id');
            $this->db->bind(':status', $tableStatus);
            $this->db->bind(':table_id', $table_id);
            $this->db->execute();

            // Commit transaction nếu mọi thứ suôn sẻ
            $this->db->query('COMMIT');
            return $order_id;

        } catch (Exception $e) {
            // Rollback nếu có bất kỳ lỗi SQL nào xảy ra
            $this->db->query('ROLLBACK');
            error_log("Create Order Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin đơn hàng đang phục vụ (chưa thanh toán) của một bàn
     */
    public function getActiveOrder($table_id) {
        $this->db->query("SELECT * FROM orders WHERE table_id = :table_id AND status IN ('pending', 'processing') ORDER BY id DESC LIMIT 1");
        $this->db->bind(':table_id', $table_id);
        return $this->db->single();
    }

    /**
     * Lấy chi tiết các món ăn trong một đơn hàng
     */
    public function getOrderDetails($order_id) {
        $this->db->query("
            SELECT od.*, p.name, p.image 
            FROM order_details od 
            JOIN products p ON od.product_id = p.id 
            WHERE od.order_id = :order_id
        ");
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }
}
?>