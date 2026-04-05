<?php
class Controller {
    // Load model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        extract($data); // Chuyển mảng data thành biến
        require_once '../app/views/' . $view . '.php';
    }
}
?>