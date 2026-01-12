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

    public function receipt($orderId)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
            exit;
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderById($orderId);
        $items = $orderModel->getOrderItems($orderId);

        if (!$order) {
            die('Order not found');
        }

        require '../app/libraries/FPDF/fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'FastFood Receipt',0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Ln(5);
        $pdf->Cell(0,10,'Order ID: '.$order['id'],0,1);
        $pdf->Cell(0,10,'User ID: '.$order['user_id'],0,1);
        $pdf->Cell(0,10,'Date: '.$order['created_at'],0,1);

        $pdf->Ln(5);
        $pdf->Cell(90,10,'Item',1);
        $pdf->Cell(30,10,'Qty',1);
        $pdf->Cell(40,10,'Price',1);
        $pdf->Cell(30,10,'Subtotal',1);
        $pdf->Ln();

        foreach($items as $i){
            $pdf->Cell(90,10,$i['name'],1);
            $pdf->Cell(30,10,$i['qty'],1);
            $pdf->Cell(40,10,'₦'.number_format($i['price'],2),1);
            $pdf->Cell(30,10,'₦'.number_format($i['subtotal'],2),1);
            $pdf->Ln();
        }

        $pdf->Ln(5);
        $pdf->Cell(0,10,'Total: ₦'.number_format($order['amount'],2),0,1,'R');

        $pdf->Output('I','Receipt_'.$order['id'].'.pdf');
    }

    public function success()
    {
        // Suppose you saved the order ID in session after checkout
        $orderId = $_SESSION['last_order_id'] ?? null;

        if ($orderId) {
            $orderModel = new Order();
            $order = $orderModel->getOrderById($orderId); // <-- make sure this method exists
        } else {
            $order = null;
        }

        // Pass $order to the view
        $this->view('user/order_success', ['order' => $order]);
    }

}
