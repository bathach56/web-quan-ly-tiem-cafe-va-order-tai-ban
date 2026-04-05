<?php
class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Lấy tất cả sản phẩm active (dùng cho Menu và POS)
    public function getAll() {
        $this->db->query('
            SELECT p.id, p.name, p.price, p.image, c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = "active"
            ORDER BY c.name ASC, p.name ASC
        ');
        return $this->db->resultSet();
    }

    // Lấy tất cả sản phẩm để quản lý Admin
    public function getAllWithCategory() {
        $this->db->query('
            SELECT p.id, p.category_id, p.name, p.price, p.image, p.status,
                   c.name AS category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            ORDER BY p.name ASC
        ');
        return $this->db->resultSet();
    }

    // Lấy 1 sản phẩm theo ID để sửa
    public function getById($id) {
        $this->db->query('
            SELECT p.id, p.category_id, p.name, p.price, p.image, p.status,
                   c.name AS category_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm mới sản phẩm
    public function create($data) {
        $this->db->query('
            INSERT INTO products (category_id, name, price, image, status)
            VALUES (:category_id, :name, :price, :image, :status)
        ');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image'] ?? 'default.jpg');
        $this->db->bind(':status', $data['status'] ?? 'active');
        return $this->db->execute();
    }

    // Cập nhật sản phẩm (hỗ trợ thay ảnh)
    public function update($id, $data) {
        $sql = '
            UPDATE products 
            SET category_id = :category_id,
                name        = :name,
                price       = :price,
                status      = :status';

        if (isset($data['image']) && $data['image'] !== '') {
            $sql .= ', image = :image';
        }

        $sql .= ' WHERE id = :id';

        $this->db->query($sql);

        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name',        $data['name']);
        $this->db->bind(':price',       $data['price']);
        $this->db->bind(':status',      $data['status']);
        $this->db->bind(':id',          $id);

        if (isset($data['image']) && $data['image'] !== '') {
            $this->db->bind(':image', $data['image']);
        }

        return $this->db->execute();
    }

    // Xóa sản phẩm
    public function delete($id) {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>