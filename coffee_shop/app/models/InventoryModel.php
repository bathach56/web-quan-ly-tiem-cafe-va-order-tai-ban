<?php
class InventoryModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Lấy danh sách nguyên liệu kèm số lượng tồn kho hiện tại
    public function getInventoryStatus() {
        $this->db->query("
            SELECT i.id, i.name, i.unit, i.min_stock,
                   COALESCE(SUM(CASE WHEN t.type = 'in' THEN t.quantity ELSE 0 END), 0) -
                   COALESCE(SUM(CASE WHEN t.type = 'out' THEN t.quantity ELSE 0 END), 0) AS current_stock
            FROM inventory_items i
            LEFT JOIN inventory_transactions t ON i.id = t.item_id
            GROUP BY i.id
            ORDER BY i.name ASC
        ");
        return $this->db->resultSet();
    }

    // Thêm nguyên liệu mới vào danh mục
    public function addItem($name, $unit, $min_stock) {
        $this->db->query('INSERT INTO inventory_items (name, unit, min_stock) VALUES (:name, :unit, :min_stock)');
        $this->db->bind(':name', $name);
        $this->db->bind(':unit', $unit);
        $this->db->bind(':min_stock', $min_stock);
        return $this->db->execute();
    }

    // Ghi nhận phiếu Nhập kho / Xuất kho
    public function addTransaction($item_id, $type, $quantity, $note, $staff_id) {
        $this->db->query('INSERT INTO inventory_transactions (item_id, type, quantity, note, staff_id) VALUES (:item_id, :type, :quantity, :note, :staff_id)');
        $this->db->bind(':item_id', $item_id);
        $this->db->bind(':type', $type);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':note', $note);
        $this->db->bind(':staff_id', $staff_id);
        return $this->db->execute();
    }
}
?>