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

         $analytics = [
            'today_orders' => $orderModel->countToday(),
            'today_sales'  => $orderModel->sumToday(),
            'month_sales'  => $orderModel->sumThisMonth()
        ];

        // PAGINAION
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;

        $result = $orderModel->paginate($page, $limit);

        $orders     = $result['data'];
        $pagination = $result['pagination'];

        $this->view('admin/dashboard', compact('orders', 'stats', 'analytics', 'pagination'));
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
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;

        $foodModel = $this->model('Food');
        $result = $foodModel->paginate($page, $limit);

        $foods      = $result['data'];
        $pagination = $result['pagination'];

        $this->view('admin/foods', compact('foods', 'pagination'));
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

    public function exportOrders()
    {
        $orderModel = $this->model('Order');
        $orders = $orderModel->allWithUsers();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Order ID','Customer','Total','Status','Date']);

        foreach ($orders as $o) {
            fputcsv($out, [
                $o['id'],
                $o['name'],
                $o['total'],
                $o['status'],
                $o['created_at']
            ]);
        }

        fclose($out);
        exit;
    }

    public function categories()
    {
        // Optional: protect admin routes
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
            exit;
        }

        // Load Category model
        $categoryModel = $this->model('Category');

        // Fetch categories (for listing)
        $categories = $categoryModel->getAll();

        // Load view
        $this->view('admin/categories', [
            'categories' => $categories
        ]);
    }

    

}
