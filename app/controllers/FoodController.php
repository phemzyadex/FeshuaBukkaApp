<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class FoodController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    // USER MENU
    // public function menu() {
    //     $foods = $this->model('Food')->all();
    //     $this->view('user/menu', compact('foods'));
    // }
    public function menu()
    {
        $foodModel = $this->model('Food');

        // Fetch foods with category name
        $foods = $foodModel->allWithCategory();

        $groupedFoods = [];
        foreach ($foods as $food) {
            $groupedFoods[$food['category_name']][] = $food;
        }
        
        // echo '<pre>';
        // print_r($groupedFoods);
        // echo '</pre>';
        // exit;

        $this->view('user/menu', compact('groupedFoods'));
    }

    // ADMIN: ADD FOOD + LIST
    // public function add()
    // {
    //     $foodModel     = $this->model('Food');
    //     $categoryModel = $this->model('Category');

    //     // Always load categories for the form
    //     $categories = $categoryModel->all();

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //         $categoryId = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    //         $name       = isset($_POST['name']) ? trim($_POST['name']) : '';
    //         $price      = isset($_POST['price']) ? trim($_POST['price']) : '';
    //         $file       = isset($_FILES['image']) ? $_FILES['image'] : null;

    //         /* ---------- VALIDATION ---------- */
    //         if ($categoryId <= 0 || $name === '' || $price === '') {
    //             $_SESSION['food_error'] = 'All fields are required';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         // Duplicate name check
    //         if ($foodModel->findByName($name)) {
    //             $_SESSION['food_error'] = 'Food with this name already exists';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         // Image validation
    //         if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    //             $_SESSION['food_error'] = 'Food image is required';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    //         if (!in_array($file['type'], $allowedTypes)) {
    //             $_SESSION['food_error'] = 'Only JPG, PNG or WEBP images allowed';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         if ($file['size'] > 2 * 1024 * 1024) {
    //             $_SESSION['food_error'] = 'Image must not exceed 2MB';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         /* ---------- UPLOAD IMAGE ---------- */
    //         $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    //         $imageName = uniqid('food_', true) . '.' . $ext;

    //         $uploadDir = __DIR__ . '/../../public/uploads';
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0755, true);
    //         }

    //         if (!move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $imageName)) {
    //             $_SESSION['food_error'] = 'Image upload failed';
    //             header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //             exit;
    //         }

    //         /* ---------- SAVE ---------- */
    //         $foodModel->create($name, $price, $imageName, $categoryId);

    //         $_SESSION['food_success'] = 'Food added successfully';
    //         header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //         exit;
    //     }

    //     // GET request
    //     $foods = $foodModel->all();
    //     $this->view('admin/foods', compact('foods', 'categories'));
    // }
    // public function add()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //         exit;
    //     }

    //     $foodModel = $this->model('Food');

    //     $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    //     $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
    //     $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    //     $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

    //     if ($categoryId <= 0 || $name === '' || $price === '') {
    //         $_SESSION['food_error'] = 'All fields are required';
    //         header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //         exit;
    //     }

    //     if ($foodModel->findByName($name)) {
    //         $_SESSION['food_error'] = 'Food with this name already exists';
    //         header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //         exit;
    //     }

    //     $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    //     $imageName = uniqid('food_', true) . '.' . $ext;

    //     $uploadDir = __DIR__ . '/../../public/uploads';
    //     if (!is_dir($uploadDir)) {
    //         mkdir($uploadDir, 0755, true);
    //     }

    //     move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $imageName);

    //     $foodModel->create($name, $price, $imageName, $categoryId);

    //     $_SESSION['food_success'] = 'Food added successfully';
    //     header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
    //     exit;
    // }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        $foodModel = $this->model('Food');

        $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? trim($_POST['price']) : '';
        $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

        // ---------- BASIC VALIDATION ----------
        if ($categoryId <= 0 || $name === '' || $price === '') {
            $_SESSION['food_error'] = 'All fields are required';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        if ($foodModel->findByName($name)) {
            $_SESSION['food_error'] = 'Food with this name already exists';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // ---------- IMAGE VALIDATION ----------
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['food_error'] = 'Image upload failed';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['food_error'] = 'Only JPG, PNG or WEBP images allowed';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['food_error'] = 'Image must not exceed 2MB';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // ---------- SAFE UPLOAD ----------
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $imageName = uniqid('food_', true) . '.' . $ext;

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            $_SESSION['food_error'] = 'Upload directory not writable';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $imageName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $_SESSION['food_error'] = 'Failed to save uploaded image';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        // ---------- SAVE TO DB ----------
        $foodModel->create($name, $price, $imageName, $categoryId);

        $_SESSION['food_success'] = 'Food added successfully';
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
        exit;
    }

  // EDIT FOOD
    public function edit($id) {
        $foodModel = $this->model('Food');
        $categoryModel = $this->model('Category');

        $food = $foodModel->find($id);
        if (!$food) {
            $_SESSION['food_error'] = "Food not found!";
            header('Location: /FastFood_MVC_Phase1_Auth/public/food/add');
            exit;
        }

        $categories = $categoryModel->all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $file  = isset($_FILES['image']) ? $_FILES['image'] : null;

            if ($name === '' || $price === '' || $category_id === '') {
                $_SESSION['food_error'] = "All fields are required!";
                header("Location: /FastFood_MVC_Phase1_Auth/public/food/edit/$id");
                exit;
            }

            // Prevent duplicate name
            $existing = $foodModel->findByName($name);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['food_error'] = "Another food with this name exists!";
                header("Location: /FastFood_MVC_Phase1_Auth/public/food/edit/$id");
                exit;
            }

            $imageName = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('food_', true) . '.' . $ext;
                $uploadDir = realpath(__DIR__ . '/../../public/uploads') ?: mkdir(__DIR__ . '/../../public/uploads', 0755, true);
                $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $imageName;
                move_uploaded_file($file['tmp_name'], $uploadPath);
            }

            $foodModel->update($id, $name, $price, $category_id, $imageName);

            $_SESSION['food_success'] = "Food updated successfully!";
            header("Location: /FastFood_MVC_Phase1_Auth/public/food/add");
            exit;
        }

        $this->view('admin/edit_food', compact('food', 'categories'));
    }

    public function delete($id) {
        $this->model('Food')->delete($id);
        $_SESSION['food_success'] = 'Food deleted successfully';
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
        exit;
    }

    public function index() {
        $categories = $this->model('Category')->all();
        

        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit;
    }

    public function foods()
    {
        // Food model
        $foodModel = $this->model('Food');
        $foods = $foodModel->all();

        // Category model
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all(); // fetch all categories

        // Pagination (optional)
        $pagination = $foodModel->getPagination(); 

        // Pass everything to the view
        $this->view('admin/foods', compact('foods', 'categories', 'pagination'));
    }

}
