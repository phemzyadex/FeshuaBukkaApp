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
            "INSERT INTO orders (user_id, total, status, payment_status, created_at) VALUES (?, ?, 'Pending', 'unpaid', NOW())"
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
        $stmt = $this->db->query("SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
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

    public function itemsByOrder($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.qty, oi.price, f.name
            FROM order_items oi
            JOIN foods f ON f.id = oi.food_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // ✅ Get all orders (ADMIN)
    public function all() {
        $stmt = $this->db->query("
            SELECT o.*, u.name
            FROM orders o
            JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Get items for a single order
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

    // ✅ Dashboard stats
    public function stats() {
        return [
            'orders' => $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'sales'  => $this->db->query("SELECT IFNULL(SUM(total),0) FROM orders")->fetchColumn()
        ];
    }
}
