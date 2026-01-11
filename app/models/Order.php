<?php
class Order {
    private $db;

    public function __construct() {
        $config = require '../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']}",
            $config['user'],
            $config['pass']
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Create a new order with cart items
    public function create($userId, $cart, $total) {
        $stmt = $this->db->prepare(
            "INSERT INTO orders (user_id, total, status, payment_status, created_at) 
             VALUES (?, ?, 'Pending', 'unpaid', NOW())"
        );
        $stmt->execute([$userId, $total]);

        $orderId = $this->db->lastInsertId();

        $stmtItem = $this->db->prepare(
            "INSERT INTO order_items (order_id, food_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        foreach ($cart as $item) {
            $stmtItem->execute([
                $orderId,
                $item['id'],
                $item['qty'],
                $item['price']
            ]);
        }

        return $orderId;
    }

    // Mark an order as paid
    public function markPaid($orderId, $ref) {
        $stmt = $this->db->prepare(
            "UPDATE orders SET payment_status='paid', payment_ref=? WHERE id=?"
        );
        $stmt->execute([$ref, $orderId]);
    }

    // Fetch all orders for a user
    public function getOrdersByUser($userId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all orders for admin dashboard
    public function getAllOrders() {
        $stmt = $this->db->query("
            SELECT o.*, u.name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compute total amount of cart
    public function cartTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['qty'] * $item['price'];
        }
        return $total;
    }

    // Get items by order
    public function itemsByOrder($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.quantity AS qty, oi.price, f.name
            FROM order_items oi
            JOIN foods f ON f.id = oi.food_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all orders (ADMIN)
    public function all() {
        $stmt = $this->db->query("
            SELECT o.*, u.name
            FROM orders o
            JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get items for a single order
    public function items($orderId) {
        $stmt = $this->db->prepare("
            SELECT f.name, oi.quantity AS qty, oi.price
            FROM order_items oi
            JOIN foods f ON f.id = oi.food_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Dashboard stats
    public function stats() {
        return [
            'orders' => $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'sales'  => $this->db->query("SELECT IFNULL(SUM(total),0) FROM orders")->fetchColumn()
        ];
    }

    // Count orders today
    public function countToday() {
        $stmt = $this->db->query("
            SELECT COUNT(*) FROM orders
            WHERE DATE(created_at) = CURDATE()
        ");
        return (int) $stmt->fetchColumn();
    }

    // Sum of orders today
    public function sumToday() {
        $stmt = $this->db->query("
            SELECT IFNULL(SUM(total),0) FROM orders
            WHERE DATE(created_at) = CURDATE()
        ");
        return $stmt->fetchColumn();
    }

    // Sum of orders this month
    public function sumThisMonth() {
        $stmt = $this->db->query("
            SELECT IFNULL(SUM(total),0) FROM orders
            WHERE MONTH(created_at)=MONTH(CURDATE())
            AND YEAR(created_at)=YEAR(CURDATE())
        ");
        return $stmt->fetchColumn();
    }

    // Get all orders with user info (ADMIN)
    public function allWithUsers() {
        $stmt = $this->db->query("
            SELECT o.*, u.name
            FROM orders o
            JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $total = $this->db->query("SELECT COUNT(*) FROM orders")
                        ->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT orders.*, users.name
            FROM orders
            JOIN users ON users.id = orders.user_id
            ORDER BY orders.created_at DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'pagination' => [
                'current' => $page,
                'pages'   => ceil($total / $limit)
            ]
        ];
    }
    // public function paginate($page = 1, $limit = 5, $date = null) {
    //     $offset = ($page - 1) * $limit;
    //     $sql = "SELECT * FROM orders WHERE 1";

    //     if ($date) {
    //         $sql .= " AND DATE(created_at) = :date";
    //     }

    //     $sql .= " ORDER BY created_at DESC LIMIT :offset, :limit";

    //     $stmt = $this->db->prepare($sql);
    //     if ($date) {
    //         $stmt->bindValue(':date', $date);
    //     }
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->execute();

    //     $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Pagination info
    //     $total = 0;
    //     if ($date) {
    //         $stmtTotal = $this->db->prepare("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = :date");
    //         $stmtTotal->bindValue(':date', $date);
    //         $stmtTotal->execute();
    //         $row = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    //         if ($row && isset($row['total'])) {
    //             $total = (int) $row['total'];
    //         }
    //     } else {
    //         $row = $this->db->query("SELECT COUNT(*) as total FROM orders")->fetch(PDO::FETCH_ASSOC);
    //         if ($row && isset($row['total'])) {
    //             $total = (int) $row['total'];
    //         }
    //     }

    //     $pages = ceil($total / $limit);

    //     return [
    //         'data' => $orders,
    //         'pagination' => [
    //             'current' => $page,
    //             'pages'   => $pages
    //         ]
    //     ];
    // }

    // public function paginate($page = 1, $limit = 5) {
    //     $offset = ($page - 1) * $limit;

    //     // Base query: join users to get customer name
    //     $sql = "SELECT o.*, u.name 
    //             FROM orders o
    //             JOIN users u ON u.id = o.user_id
    //             WHERE 1";

    //     // // Apply date filter if provided
    //     // if ($date) {
    //     //     $sql .= " AND DATE(o.created_at) = :date";
    //     // }

    //     // Add pagination
    //     $sql .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";

    //     // Prepare and bind
    //     $stmt = $this->db->prepare($sql);
    //     // if ($date) {
    //     //     $stmt->bindValue(':date', $date);
    //     // }
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    //     $stmt->execute();
    //     $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // ------------------------
    //     // Get total count for pagination
    //     // ------------------------
    //     // if ($date) {
    //     //     $stmtTotal = $this->db->prepare("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = :date");
    //     //     $stmtTotal->bindValue(':date', $date);
    //     //     $stmtTotal->execute();
    //     //     $total = (int) $stmtTotal->fetchColumn();
    //     // } else {
    //         $total = (int) $this->db->query("SELECT COUNT(*) as total FROM orders")->fetchColumn();
    //     // }

    //     $pages = ceil($total / $limit);

    //     return [
    //         'data' => $orders,
    //         'pagination' => [
    //             'current' => $page,
    //             'pages'   => $pages,
    //             'total'   => $total
    //         ]
    //     ];
    // }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM orders");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

}
