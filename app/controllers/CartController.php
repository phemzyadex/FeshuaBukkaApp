<?php
class CartController extends Controller {
     public function __construct() {
        $this->requireLogin(); // 🔒 Protect all cart pages
    }
    public function add($foodId) {
        $cartModel = $this->model('Cart');
        $cartModel->addItem($foodId, 1); // default quantity 1
        $item_added = true; // send to view
        $cartItems = $cartModel->all();
        // Call parent view method correctly
        parent::view('user/cart', compact('cartItems','item_added'));
    }

    // Fixed method signature to match parent Controller
    public function view($view = 'user/cart', $data = []) {
        $cart = $this->model('Cart')->all();
        $total = $this->model('Cart')->total();
        $data = array_merge($data, compact('cart', 'total'));
        parent::view($view, $data);
    }
     public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit();
    }
}
