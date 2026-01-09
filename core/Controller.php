<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
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
}
