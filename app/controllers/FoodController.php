<?php
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

            $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

            // ---------- BASIC VALIDATION ----------
            if ($name === '' || $price === '') {
                $_SESSION['food_error'] = 'Food name and price are required';
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
            $uploadPath = '../public/uploads/' . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['food_error'] = 'Image upload failed';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ---------- SAVE TO DATABASE ----------
            $this->model('Food')->create($name, $price, $imageName);

            $_SESSION['food_success'] = 'Food added successfully';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // GET request – show page
        $foods = $this->model('Food')->all();
        $this->view('admin/foods', compact('foods'));
    }

    public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit;
    }
}
