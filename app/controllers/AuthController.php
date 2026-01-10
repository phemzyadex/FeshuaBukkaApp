<?php
class AuthController extends Controller {


    public function login() {
    $login_success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $this->model('User')->login($_POST['email'], $_POST['password']);
        if ($user) {
            $_SESSION['user'] = $user;
            $login_success = true;
        } else {
            $login_success = false;
        }
    }

    $this->view('user/login', compact('login_success'));
}

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name  = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $pass  = $_POST['password'];

            $userModel = $this->model('User');

            // ---------- Validation ----------
            if ($name === '' || $email === '' || $phone === '' || $pass === '') {
                $error = "All fields are required";
                $this->view('auth/register', compact('error', 'name', 'email', 'phone'));
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $error = "Phone number must be 10-15 digits";
                $this->view('auth/register', compact('error', 'name', 'email', 'phone'));
                return;
            }

            if ($userModel->existsByEmail($email)) {
                $error = "Email already exists!";
                $this->view('auth/register', compact('error', 'name', 'email', 'phone'));
                return;
            }

            if ($userModel->existsByPhone($phone)) {
                $error = "Phone number already exists!";
                $this->view('auth/register', compact('error', 'name', 'email', 'phone'));
                return;
            }

            // ---------- Save User ----------
            $userModel->create($name, $email, $phone, $pass);
            $_SESSION['register_success'] = true;
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/register');
            exit;
        }

        // GET request
        $this->view('auth/register');
    }
     public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit();
    }
    
    public function logout()
    {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Prevent browser caching
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Redirect to login page
        header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
        exit();
    }

}
