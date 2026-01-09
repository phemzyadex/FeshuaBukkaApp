<?php
class OrderController extends Controller {
    public function __construct() {
        $this->requireLogin(); // 🔒 Protect all cart pages
    }
    public function checkout() {
        $orderModel = $this->model('Order');
        $cart = $this->model('Cart')->all();
        $total = $orderModel->cartTotal($cart);
        $orderId = $orderModel->create($_SESSION['user']['id'], $cart, $total);

        // Clear cart after placing order
        $this->model('Cart')->clear();

        $order_placed = true;
        $orders = $orderModel->getOrdersByUser($_SESSION['user']['id']);
        $this->view('user/track', compact('orders','order_placed'));
    }

    public function markPaid($orderId, $ref) {
        $orderModel = $this->model('Order');
        $orderModel->markPaid($orderId, $ref);
        $this->redirect('/admin/dashboard');
    }
     public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit();
    }
}
