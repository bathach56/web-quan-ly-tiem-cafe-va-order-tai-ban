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
            
            // 1. [TRÁNH TRÙNG LẶP]: Xóa hóa đơn đang treo của bàn này (nếu có) trước khi tạo mới
            $this->db->query("SELECT id FROM orders WHERE table_id = :table_id AND status IN ('pending', 'processing') LIMIT 1");
            $this->db->bind(':table_id', $table_id);
            $oldOrder = $this->db->single();
            
            if ($oldOrder) {
                $this->db->query("DELETE FROM order_details WHERE order_id = :order_id");
                $this->db->bind(':order_id', $oldOrder['id']);
                $this->db->execute();
                
                $this->db->query("DELETE FROM orders WHERE id = :order_id");
                $this->db->bind(':order_id', $oldOrder['id']);
                $this->db->execute();
            }

            // 2. Tạo order chính với $status động
            $this->db->query('
                INSERT INTO orders (table_id, staff_id, total_amount, status)
                VALUES (:table_id, :staff_id, 0, :status)
            ');
            $this->db->bind(':table_id', $table_id);
            $this->db->bind(':staff_id', $staff_id); // Đã bỏ PDO::PARAM_INT để chấp nhận khách tự order (null)
            $this->db->bind(':status', $status);
            $this->db->execute();
            
            $order_id = $this->db->lastInsertId(); // Lấy ID qua hàm public ở class Database
            $total_amount = 0;

            // 3. Thêm chi tiết đơn hàng
            foreach ($items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $total_amount += $subtotal;

                $this->db->query('
                    INSERT INTO order_details (order_id, product_id, quantity, unit_price)
                    VALUES (:order_id, :product_id, :quantity, :unit_price)
                ');
                $this->db->bind(':order_id', $order_id);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->execute();
            }

            // 4. Cập nhật tổng tiền cho order
            $this->db->query('UPDATE orders SET total_amount = :total WHERE id = :order_id');
            $this->db->bind(':total', $total_amount);
            $this->db->bind(':order_id', $order_id);
            $this->db->execute();

            // 5. CẬP NHẬT TRẠNG THÁI BÀN (Nếu Tính Tiền -> Bàn Trống, Còn Lại -> Đang Có Khách)
            $tableStatus = ($status === 'completed') ? 'available' : 'occupied';
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