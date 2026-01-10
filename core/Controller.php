<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        // global cart count
        $data['cartCount'] = $this->cartCount();

        extract($data);
        require_once '../app/views/' . $view . '.php';
    }

    protected function redirect($path) {
        header('Location: ' . $path);
        exit;
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user'])) {
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
            exit();
        }
    }

    protected function requireAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
            exit();
        }
    }

    protected function cartCount() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return 0;
        }

        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

}
