<?php
class CartController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    // Add to cart
    public function add($foodId) {
        $cart = $this->model('Cart');
        $cart->addItem($foodId, 1);

        $_SESSION['cart_success'] = 'Item added to cart';
        header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
        exit;
    }

    // /cart or /cart/index
    public function index() {
        $cartModel = $this->model('Cart');

        $cartItems = $cartModel->all();
        $total = $cartModel->total();

        $this->view('user/cart', compact('cartItems', 'total'));
    }
    
     // Remove item
    public function remove($id) {
        $this->model('Cart')->remove($id);
        header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
        exit;
    }

    // Increase quantity
    public function increase($id) {
        $this->model('Cart')->increase($id);
        header('Location: /FastFood_MVC_Phase1_Auth/public/checkout');
        exit;
    }

    // Decrease quantity
    public function decrease($id) {
        $this->model('Cart')->decrease($id);
        header('Location: /FastFood_MVC_Phase1_Auth/public/checkout');
        exit;
    }

}
