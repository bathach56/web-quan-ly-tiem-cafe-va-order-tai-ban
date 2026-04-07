<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // =======================================================
    // 1. CÁC HÀM XỬ LÝ ĐĂNG NHẬP & TÀI KHOẢN (CŨ CỦA BẠN)
    // =======================================================

    // Kiểm tra đăng nhập
    public function login($username, $password) {
        $this->db->query('
            SELECT id, username, full_name, role, password, status 
            FROM users 
            WHERE username = :username 
            LIMIT 1
        ');
        $this->db->bind(':username', $username);
        $user = $this->db->single();

        // Kiểm tra mật khẩu băm (hash) HOẶC mật khẩu thô (text)
        if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
            // Tùy chọn: Nếu tài khoản bị khóa, không cho đăng nhập
            if ($user['status'] === 'inactive') {
                return 'locked';
            }
            return $user;
        }
        
        return false;
    }

    // Kiểm tra username tồn tại (cho quên mật khẩu)
    public function findByUsername($username) {
        $this->db->query('SELECT id, full_name, username FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    // =======================================================
    // 2. CÁC HÀM XỬ LÝ QUẢN LÝ NHÂN SỰ DÀNH CHO ADMIN
    // =======================================================

    // Lấy toàn bộ danh sách tài khoản
    public function getAllStaff() {
        $this->db->query("SELECT id, username, full_name, role, status, created_at FROM users ORDER BY role ASC, created_at DESC");
        return $this->db->resultSet();
    }

    // Thêm nhân viên mới
    public function create($data) {
        $this->db->query('INSERT INTO users (username, password, full_name, role, status) VALUES (:username, :password, :full_name, :role, :status)');
        $this->db->bind(':username', $data['username']);
        // Băm mật khẩu để bảo mật trước khi lưu vào CSDL
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    // Cập nhật thông tin nhân viên
    public function update($id, $data) {
        $query = 'UPDATE users SET username = :username, full_name = :full_name, role = :role, status = :status';
        
        // Chỉ cập nhật mật khẩu nếu Admin có gõ mật khẩu mới
        if (!empty($data['password'])) {
            $query .= ', password = :password';
        }
        $query .= ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $id);

        if (!empty($data['password'])) {
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        }
        return $this->db->execute();
    }

    // Xóa nhân viên
    public function delete($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>