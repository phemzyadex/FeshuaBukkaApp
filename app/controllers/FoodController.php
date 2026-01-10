<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class FoodController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    // USER MENU
    public function menu() {
        $foods = $this->model('Food')->all();
        $this->view('user/menu', compact('foods'));
    }

    // ADMIN: ADD FOOD + LIST
    public function add() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $categoryId = (int) $_POST['category_id'];
            $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

            // ---------- BASIC VALIDATION ----------
            if ($name === '' || $price === '') {
                $_SESSION['food_error'] = 'Food name and price are required';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ---------- DUPLICATE CHECK ----------
            $existing = $this->model('Food')->findByName($name);
            if ($existing) {
                $_SESSION['food_error'] = 'Food with this name already exists';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['food_error'] = 'Food image is required';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ---------- IMAGE VALIDATION ----------
            $allowedTypes = array('image/jpeg', 'image/png', 'image/webp');
            $maxSize = 2 * 1024 * 1024; // 2MB

            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['food_error'] = 'Only JPG, PNG or WEBP images allowed';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            if ($file['size'] > $maxSize) {
                $_SESSION['food_error'] = 'Image must not exceed 2MB';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ---------- SAFE IMAGE UPLOAD ----------
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('food_', true) . '.' . $ext;

            // Resolve absolute path
            $uploadDir = realpath(__DIR__ . '/../../public/uploads');
            if ($uploadDir === false) {
                mkdir(__DIR__ . '/../../public/uploads', 0755, true);
                $uploadDir = realpath(__DIR__ . '/../../public/uploads');
            }

            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['food_error'] = 'Image upload failed';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ---------- SAVE TO DATABASE ----------
            $this->model('Food')->create($name, $price, $imageName, $categoryId);

            $_SESSION['food_success'] = 'Food added successfully';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // GET request – show page
        $foods = $this->model('Food')->all();
        $this->view('admin/foods', compact('foods'));
    }

  // EDIT FOOD
    public function edit($id) {

        $foodModel = $this->model('Food');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

            if ($name === '' || $price === '') {
                $_SESSION['food_error'] = 'Name and price required';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // Prevent duplicate on edit
            $existing = $this->model('Food')->findByName($name);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['food_error'] = 'Another food with this name exists';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            $imageName = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('food_', true) . '.' . $ext;

                $uploadDir = realpath(__DIR__ . '/../../public/uploads');
                if ($uploadDir === false) mkdir(__DIR__ . '/../../public/uploads', 0755, true);

                $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $imageName;
                move_uploaded_file($file['tmp_name'], $uploadPath);
            }

            // Update database
            $this->model('Food')->update($id, $name, $price, $imageName);

            $_SESSION['food_success'] = 'Food updated successfully';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }
        // ---------- GET: SHOW FORM ----------
        $food = $foodModel->findById($id);

        if (!$food) {
            die('Food not found');
        }

        $this->view('admin/edit_food', compact('food'));
    }
    public function delete($id) {
        $this->model('Food')->delete($id);
        $_SESSION['food_success'] = 'Food deleted successfully';
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
        exit;
    }

    public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit;
    }
}
