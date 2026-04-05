<?php
class App {
    protected $controller = 'Auth';     // Controller mặc định (trang đăng nhập)
    protected $method     = 'admin';    // Method mặc định (gọi hàm admin_login)
    protected $params     = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Xác định Controller
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
            
            // [VÁ LỖI]: Khi đổi sang một Controller mới (ví dụ: Product), 
            // bắt buộc phải reset method mặc định về 'index' (thay vì 'admin' của Auth).
            $this->method = 'index';
        }

        // Load và khởi tạo Controller
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Xác định Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Xác định Params (các tham số còn lại trên URL, ví dụ ID sản phẩm)
        // Nếu $url có dữ liệu thì reset lại key (mảng tuần tự), nếu không thì mảng rỗng
        $this->params = $url ? array_values($url) : [];

        // 4. Gọi hàm (Method) bên trong Controller và truyền tham số (Params) vào
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Hàm cắt URL thành mảng: /controller/method/param1/param2...
    public function parseUrl() {
        if (isset($_GET['url'])) {
            // Xóa dấu '/' thừa ở cuối, làm sạch chuỗi URL và cắt thành mảng
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>