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

    public function verify($reference = null)
    {
        if (!$reference) {
            header('Location: /FastFood_MVC_Phase1_Auth/public/food/menu');
            exit;
        }

       $paymentModel = $this->model('Payment');

        // 🔒 Reject already-used reference
        if ($paymentModel->referenceExists($reference)) {
            $_SESSION['error'] = 'Transaction already processed.';
            header('Location: /FastFood_MVC_Phase1_Auth/public/food/menu');
            exit;
        }

        // 🔁 Verify with Paystack
        $secretKey = 'sk_test_2264d0138bfcd620428471a5e9f1c8e58faa05a7';

        $ch = curl_init("https://api.paystack.co/transaction/verify/$reference");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $secretKey",
                "Cache-Control: no-cache"
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        if (
            !$response['status'] ||
            $response['data']['status'] !== 'success'
        ) {
            $_SESSION['error'] = 'Payment verification failed.';
            header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
            exit;
        }

        $amount = $response['data']['amount'] / 100;
        $userId = $_SESSION['user_id'];

        // 🔐 CART TOTAL VALIDATION 
        if (empty($_SESSION['cart'])) {
            die('Cart is empty');
        }

        $cartTotal = array_sum(array_map(function ($i) {
            return $i['price'] * $i['qty'];
        }, $_SESSION['cart']));

        if ((float)$cartTotal !== (float)$amount) {
            die('Amount mismatch');
        }
        if (abs($cartTotal - $amount) > 0.01) {
            die('Amount mismatch');
        }
        
        // ✅ Save payment reference
        $paymentModel->save([
            'user_id'   => $userId,
            'reference' => $reference,
            'amount'    => $amount,
            'status'    => 'success'
        ]);

        // ✅ Save order
        $orderModel = $this->model('Order');
        $orderId = $orderModel->create([
            'user_id' => $userId,
            'amount'  => $amount,
            'status'  => 'paid'
        ]);

        foreach ($_SESSION['cart'] as $item) {
            $orderModel->addItem(
                $orderId,
                $item['id'],
                $item['qty'],
                $item['price']
            );
        }

        if ($response['status'] === true &&
            $response['data']['status'] === 'success'
        ) {
            // ✅ Clear cart
            unset($_SESSION['cart']);

            $_SESSION['success'] = 'Payment successful. Order placed!';
            // ✅ Redirect to order_success
            header('Location: /FastFood_MVC_Phase1_Auth/public/food/order_success');
            exit;
        } else {
            // ❌ Failed payment → back to checkout
            //header('Location: /FastFood_MVC_Phase1_Auth/public/cart');
            // ❌ remove below line for production
            header('Location: /FastFood_MVC_Phase1_Auth/public/order_success');
            exit;
        }
    }

    private function completeOrder($reference, $amount)
    {
        $userId = $_SESSION['user_id'];
        $cart   = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            die('Cart is empty');
        }

        $orderId = $this->orderModel->create([
            'user_id'   => $userId,
            'reference' => $reference,
            'amount'    => $amount,
            'status'    => 'paid'
        ]);

        foreach ($cart as $item) {
            $this->orderModel->addItem(
                $orderId,
                $item['id'],
                $item['qty'],
                $item['price']
            );
        }

        unset($_SESSION['cart']);

        header('Location: /FastFood_MVC_Phase1_Auth/public/orders/success');
        exit;
    }

    private function placeOrderAfterPayment($reference, $amount)
    {
        $userId = $_SESSION['user_id'];
        $cart   = $_SESSION['cart'];

        $orderId = $this->orderModel->create([
            'user_id'   => $userId,
            'reference' => $reference,
            'amount'    => $amount,
            'status'    => 'paid'
        ]);

        foreach ($cart as $item) {
            $this->orderModel->addItem(
                $orderId,
                $item['id'],
                $item['qty'],
                $item['price']
            );
        }

        unset($_SESSION['cart']);

        header('Location: /FastFood_MVC_Phase1_Auth/public/orders/success');
        exit;
    }
}
