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
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $userModel = $this->model('User');

        // Check if email already exists
        if ($userModel->exists($email)) {
            // Email already registered
            $error = "Email is already registered. Try logging in.";
            $this->view('user/register', compact('error', 'name', 'email'));
            return;
        }

        // Register new user
        $userModel->register($name, $email, $password);

        // Success: redirect to login with a toast
        $_SESSION['register_success'] = true;
        header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
        exit;
        }

        // Show registration form
        $this->view('user/register');
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
