<?php
class AdminController extends Controller {

    public function __construct() {
        $this->requireAdmin(); // 🔒 Admin-only
        // Make sure only admin can access
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            die('Access denied');
        }
    }

    // Admin Dashboard
    public function dashboard() {
        $adminModel = $this->model('Admin');

        // Fetch stats
        $stats = $adminModel->stats();

        // Fetch all orders
        $orders = $adminModel->orders();

        // Pass data to view
        $this->view('admin/dashboard', compact('orders', 'stats'));
    }

    // Update order status
    public function updateStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
            $adminModel = $this->model('Admin');
            $adminModel->updateOrderStatus($orderId, $_POST['status']);
        }
        $this->redirect('/admin/dashboard');
    }

    // Add food page
    public function add_food() {
        $this->view('admin/add_food');
    }

   
     public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit();
    }

    public function foods() {
        $foods = $this->model('Food')->all();
        $this->view('admin/foods', compact('foods'));
    }

    public function addFood() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $foodModel = $this->model('Food');
        $name  = trim($_POST['name']);
        $price = $_POST['price'];

        // 🔴 DUPLICATE CHECK
        if ($foodModel->existsByName($name)) {
            $_SESSION['food_error'] = "Food already exists!";
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // Upload image
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            '../public/uploads/' . $image
        );

        $foodModel->create($name, $price, $image);

        $_SESSION['food_success'] = "Food added successfully!";
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
        exit;
        }

        $foods = $this->model('Food')->all();
        $this->view('admin/add_food', compact('foods'));
    }


}
