<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Kiểm tra đăng nhập
    public function login($username, $password) {
        $this->db->query('
            SELECT id, username, full_name, role, password 
            FROM users 
            WHERE username = :username 
            LIMIT 1
        ');
        $this->db->bind(':username', $username);
        $user = $this->db->single();

        // ĐÃ SỬA Ở ĐÂY: Chấp nhận cả mật khẩu đã băm (hash) HOẶC mật khẩu thô (text)
        if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
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
}
?>