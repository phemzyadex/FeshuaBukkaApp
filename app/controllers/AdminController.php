<?php
class AdminController extends Controller {

    public function __construct() {
        $this->requireAdmin();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            die('Access denied');
        }
    }

    // =======================
    // ADMIN DASHBOARD
    // =======================
    // public function dashboard() {

    //     $orderModel = $this->model('Order');
    //     $userModel  = $this->model('User');

    //     // PAGINATION
    //     $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    //     $limit = 5;
    //     $result = $orderModel->paginate($page, $limit);

    //     $orders     = $result['data'];
    //     $pagination = $result['pagination'];

    //     // Attach items to paginated orders
    //     foreach ($orders as &$order) {
    //         $order['items'] = $orderModel->items($order['id']);
    //     }

    //     // Dashboard stats
    //     $orderStats = $orderModel->stats();
    //     $stats = [
    //         'orders' => $orderModel->countAll(), // total orders
    //         'sales'  => isset($orderStats['sales']) ? $orderStats['sales'] : 0,
    //         'users'  => $userModel->count()
    //     ];

    //     $analytics = [
    //         'today_orders' => $orderModel->countToday(),
    //         'today_sales'  => $orderModel->sumToday(),
    //         'month_sales'  => $orderModel->sumThisMonth()
    //     ];

    //     $paymentModel = $this->model('Payment');
    //     $orderModel   = $this->model('Order');

    //     // Fetch total revenue
    //     $totalRevenue = $paymentModel->getTotalRevenue();

    //     // Total orders
    //     $totalOrders = $orderModel->getTotalOrders();

    //     // Recent payments (last 10)
    //     $recentPayments = $paymentModel->getRecentPayments(10);

    //     $data = [
    //         'totalRevenue'   => $totalRevenue,
    //         'totalOrders'    => $totalOrders,
    //         'recentPayments' => $recentPayments
    //     ];

    //     $this->view('admin/dashboard', compact('orders', 'stats', 'analytics', 'pagination', 'data'));
    // }
    public function dashboard() {
        $orderModel = $this->model('Order');
        $userModel  = $this->model('User');
        $paymentModel = $this->model('Payment');

        // PAGINATION
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;
        $result = $orderModel->paginate($page, $limit);

        $orders     = $result['data'];
        $pagination = $result['pagination'];

        // Attach items to orders
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->items($order['id']);
        }

        // Dashboard stats
        $orderStats = $orderModel->stats();
        $stats = [
            'orders' => $orderModel->countAll(),
            'sales'  => $orderStats['sales'] ?? 0,
            'users'  => $userModel->count()
        ];

        $analytics = [
            'today_orders' => $orderModel->countToday(),
            'today_sales'  => $orderModel->sumToday(),
            'month_sales'  => $orderModel->sumThisMonth()
        ];

        // Finance summary
        $totalRevenue   = $paymentModel->getTotalRevenue();
        $totalOrders    = $orderModel->getTotalOrders();
        $recentPayments = $paymentModel->getRecentPayments(10);

        // Merge all variables for view
        $viewData = array_merge(
            [
                'orders'     => $orders,
                'stats'      => $stats,
                'analytics'  => $analytics,
                'pagination' => $pagination,
                'totalRevenue'=> $totalRevenue,
                'totalOrders' => $totalOrders,
                'recentPayments' => $recentPayments
            ]
        );

        $this->view('admin/dashboard', $viewData);
    }

    // public function dashboard() {
    //     $orderModel = $this->model('Order');
    //     $userModel  = $this->model('User');

    //     // -----------------------------
    //     // PAGINATION
    //     // -----------------------------
    //     $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    //     $limit = 5;

    //     // Default date filter: today
    //     $filterDate = isset($_GET['filterDate']) && $_GET['filterDate'] !== ''
    //                 ? $_GET['filterDate']
    //                 : date('Y-m-d');

    //     // Fetch paginated orders with optional date filter
    //     //$result = $orderModel->paginate($page, $limit, $filterDate);
    //     $result = $orderModel->paginate($page, $limit);
    //     $orders = $result['data'];
    //     $pagination = $result['pagination'];

    //     // -----------------------------
    //     // ATTACH ITEMS TO ORDERS
    //     // -----------------------------
    //     foreach ($orders as &$order) {
    //         $order['items'] = $orderModel->items($order['id']);
    //         // Ensure 'name' key exists
    //         $order['name'] = isset($order['name']) ? $order['name'] : 'Unknown';
    //     }

    //     // -----------------------------
    //     // DASHBOARD STATS
    //     // -----------------------------
    //     $orderStats = $orderModel->stats();
    //     $stats = [
    //         'orders' => $orderModel->countAll(), // total orders
    //         'sales'  => isset($orderStats['sales']) ? $orderStats['sales'] : 0,
    //         'users'  => $userModel->count()
    //     ];

    //     $analytics = [
    //         'today_orders' => $orderModel->countToday(),
    //             'today_sales'  => $orderModel->sumToday(),
    //             'month_sales'  => $orderModel->sumThisMonth()
    //         ];

    //         // -----------------------------
    //         // PASS TO VIEW
    //         // -----------------------------
    //         $this->view('admin/dashboard', compact('orders', 'stats', 'analytics', 'pagination', 'filterDate'));
    //     }



    // // =======================
    // UPDATE ORDER STATUS
    // =======================
    public function updateStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
            $this->model('Admin')->updateOrderStatus($orderId, $_POST['status']);
        }

        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/dashboard');
        exit;
    }

    // =======================
    // FOOD MANAGEMENT
    // =======================
    public function foods() {
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;

        $foodModel = $this->model('Food');
        $result = $foodModel->paginate($page, $limit);

        $foods      = $result['data'];
        $pagination = $result['pagination'];

        $this->view('admin/foods', compact('foods', 'pagination'));
    }

    public function addFood() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $foodModel = $this->model('Food');
            // $name  = trim($_POST['name']);
            // $price = trim($_POST['price']);
            // $file  = $_FILES['image'] ?? null;
            
            $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $file = isset($_FILES['image']) ? $_FILES['image'] : null;
            
            // ✅ Duplicate check
            if ($foodModel->existsByName($name)) {
                $_SESSION['food_error'] = 'Food already exists';
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
                exit;
            }

            // ✅ Upload image
            $foodModel->create($name, $price, $file);

            $_SESSION['food_success'] = 'Food added successfully';
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/foods');
            exit;
        }

        $this->view('admin/add_food');
    }

    // =======================
    // DEFAULT REDIRECT
    // =======================
    public function index() {
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/dashboard');
        exit;
    }

    public function exportOrders()
    {
        $orderModel = $this->model('Order');
        $orders = $orderModel->allWithUsers();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Order ID','Customer','Total','Status','Date']);

        foreach ($orders as $o) {
            fputcsv($out, [
                $o['id'],
                $o['name'],
                $o['total'],
                $o['status'],
                $o['created_at']
            ]);
        }

        fclose($out);
        exit;
    }

    public function categories()
    {
        // Optional: protect admin routes
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /FastFood_MVC_Phase1_Auth/public/auth/login');
            exit;
        }

        // Load Category model
        $categoryModel = $this->model('Category');

        // Fetch categories (for listing)
        $categories = $categoryModel->getAll();

        // Load view
        $this->view('admin/categories', [
            'categories' => $categories
        ]);
    }

    public function finance()
    {
        $finance = $this->model('Finance');

        $data = [
            'summary' => $finance->summary(),
            'today'   => $finance->today(),
            'records' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['records'] = $finance->byDateRange(
                $_POST['start_date'],
                $_POST['end_date']
            );
        }

        $this->view('admin/finance', $data);
    }

    // =======================
    // FINANCE REPORT (FILTER + PDF)
    // =======================
    public function financeReport() {
        $paymentModel = $this->model('Payment');
        $orderModel   = $this->model('Order');

        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate   = $_GET['end'] ?? date('Y-m-d');

        // Fetch data
        $totalRevenue = $paymentModel->getRevenueByDateRange($startDate, $endDate);
        $totalOrders  = $orderModel->getOrdersByDateRange($startDate, $endDate);
        $payments     = $paymentModel->getPaymentsByDateRange($startDate, $endDate);

        // Pass to view
        $this->view('admin/finance_report', [
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalOrders'  => $totalOrders,
            'payments'     => $payments
        ]);
    }

    public function financeReportPDF() {
        $paymentModel = $this->model('Payment');
        $orderModel   = $this->model('Order');

        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate   = $_GET['end'] ?? date('Y-m-d');

        $totalRevenue = $paymentModel->getRevenueByDateRange($startDate, $endDate);
        $totalOrders  = $orderModel->getOrdersByDateRange($startDate, $endDate);
        $payments     = $paymentModel->getPaymentsByDateRange($startDate, $endDate);

        require '../app/libraries/FPDF/fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,"Finance Report ($startDate to $endDate)",0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Ln(5);
        $pdf->Cell(0,10,"Total Revenue: ₦".number_format($totalRevenue,2),0,1);
        $pdf->Cell(0,10,"Total Orders: $totalOrders",0,1);
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(50,10,'Reference',1);
        $pdf->Cell(30,10,'User ID',1);
        $pdf->Cell(40,10,'Amount',1);
        $pdf->Cell(60,10,'Date',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',12);
        foreach($payments as $p){
            $pdf->Cell(50,10,$p['reference'],1);
            $pdf->Cell(30,10,$p['user_id'],1);
            $pdf->Cell(40,10,'₦'.number_format($p['amount'],2),1);
            $pdf->Cell(60,10,$p['created_at'],1);
            $pdf->Ln();
        }

        $pdf->Output('I',"FinanceReport_{$startDate}_to_{$endDate}.pdf");
    }

    

}
