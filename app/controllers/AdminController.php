<?php
class AdminController extends Controller {

    public function __construct() {
        $this->requireAdmin();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            die('Access denied');
        }
    }

    // =======================
    // ADMIN DASHBOARD
    // =======================
    public function dashboard() {

        $orderModel = $this->model('Order');
        $userModel  = $this->model('User');

        // ✅ Get all orders
        $orders = $orderModel->all();

        // ✅ Attach items to each order
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->items($order['id']);
        }

        // ✅ Dashboard stats
        $stats = [
            'orders' => count($orders),
            'sales'  => $orderModel->stats()['sales'],
            'users'  => $userModel->count()
        ];

        $this->view('admin/dashboard', compact('orders', 'stats'));
    }

    // =======================
    // UPDATE ORDER STATUS
    // =======================
    public function updateStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
            $this->model('Admin')->updateOrderStatus($orderId, $_POST['status']);
        }

        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/dashboard');
        exit;
    }

    // =======================
    // FOOD MANAGEMENT
    // =======================
    public function foods() {
        $foods = $this->model('Food')->all();
        $this->view('admin/foods', compact('foods'));
    }

    public function addFood() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $foodModel = $this->model('Food');
            // $name  = trim($_POST['name']);
            // $price = trim($_POST['price']);
            // $file  = $_FILES['image'] ?? null;
            
            $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $file = isset($_FILES['image']) ? $_FILES['image'] : null;
            
            // ✅ Duplicate check
            if ($foodModel->existsByName($name)) {
                $_SESSION['food_error'] = 'Food already exists';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ✅ Upload image
            $foodModel->create($name, $price, $file);

            $_SESSION['food_success'] = 'Food added successfully';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        $this->view('admin/add_food');
    }

    // =======================
    // DEFAULT REDIRECT
    // =======================
    public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/dashboard');
        exit;
    }
}
