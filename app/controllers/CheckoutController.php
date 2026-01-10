<?php
class CheckoutController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        $cart = $this->model('Cart');
        $cartItems = $cart->all();
        $total = $cart->total();

        if (empty($cartItems)) {
            header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
            exit;
        }

        $this->view('user/checkout', compact('cartItems', 'total'));
    }

    public function placeOrder() {

        $cart = $this->model('Cart');
        $cartItems = $cart->all();
        $total = $cart->total();

        if (empty($cartItems)) {
            header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $orderId = $this->model('Order')->create($userId, $cartItems, $total);

        // Clear cart
        $cart->clear();

        $_SESSION['order_success'] = "Order placed successfully!";
        header('Location: /FastFood_MVC_Phase1_Auth/public/checkout/success');
        exit;
    }

    public function success() {
        $this->view('user/order_success');
    }
}
