<?php
class PaymentController extends Controller {

     public function __construct() {
        $this->requireLogin(); // 🔒 Protect all cart pages
    }
    
    public function pay() {
        if (!isset($_SESSION['user'])) {
            die('Login required');
        }

        $cart  = $this->model('Cart');
        $total = $cart->total() * 100; // Paystack uses kobo

        $ref = uniqid('ORDER_');

        $_SESSION['payment_ref'] = $ref;

        $paystack = require '../config/paystack.php';

        header("Location: https://api.paystack.co/transaction/initialize?reference=$ref");
        exit;
    }

    public function verify() {
        $ref = $_GET['reference'];
        $paystack = require '../config/paystack.php';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$ref",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$paystack['secret_key']}"
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        if ($result['data']['status'] === 'success') {

            $cart = $this->model('Cart');
            $orderModel = $this->model('Order');

            $orderId = $orderModel->create(
                $_SESSION['user']['id'],
                $cart->all(),
                $cart->total()
            );

            $this->model('Order')->markPaid($orderId, $ref);
            
            $notification = $this->model('Notification');

            // Send SMS to user
            $notification->sendSMS('+234'.$userPhone, "Your order #$orderId has been placed successfully!");

            // Send Email to user
            $notification->sendEmail($userEmail, "Order Confirmation",
                "Hello {$userName}, <br>Your order #$orderId is confirmed. Total: ₦{$cart->total()}"
            );

            $cart->clear();
            echo "Payment successful! Order confirmed.";
        }
    }
     public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/');
        exit();
    }
}





