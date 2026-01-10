<?php
class Admin {
    private $db;

    public function __construct() {
        $c = require '../config/database.php';
        $this->db = new PDO("mysql:host={$c['host']};dbname={$c['db']}", $c['user'], $c['pass']);

        // 🔥 Enable exceptions
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Fetch all orders with user info
    public function orders() {
        $stmt = $this->db->prepare("
            SELECT o.id, o.user_id, o.total, o.status, u.name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

     

    
    
    // Get dashboard statistics safely
    // public function getStats() {
    //     return [
    //         'orders' => $this->count('orders'),
    //         'users'  => $this->count('users'),
    //         'foods'  => $this->count('foods')
    //     ];
    // }
    public function getStats() {
        return [
            'orders' => $this->count('orders'),
            'paid_orders' => $this->hasColumn('orders','payment_status')
                ? $this->countWhere('orders', "payment_status='paid'")
                : 0,
            'users' => $this->count('users'),
            'foods' => $this->count('foods')
        ];
    }

 private function count($table) {
        try {
            return (int)$this->db->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    private function countWhere($table, $condition) {
        try {
            return (int)$this->db
                ->query("SELECT COUNT(*) FROM {$table} WHERE {$condition}")
                ->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    private function hasColumn($table, $column) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND COLUMN_NAME = ?
        ");
        $stmt->execute([$table, $column]);
        return $stmt->fetchColumn() > 0;
    }

    // Fetch dashboard stats
    public function stats() {
        return [
            'orders' => $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'sales'  => $this->db->query("SELECT IFNULL(SUM(total),0) FROM orders")->fetchColumn(),
            'users'  => $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'paid_orders' => $this->db->query("SELECT COUNT(*) FROM orders WHERE payment_status='paid'")->fetchColumn(),
            'pending_orders' => $this->db->query("SELECT COUNT(*) FROM orders WHERE status='Pending'")->fetchColumn()
        ];
    }

    // Update order status
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
}
