<?php
class Payment
{
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

    // Check if reference already exists
    public function referenceExists($reference)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM payments WHERE reference = ? LIMIT 1"
        );
        $stmt->execute([$reference]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // false if not found
    }

    // Save payment record
    public function save($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO payments (user_id, reference, amount, status)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['user_id'],
            $data['reference'],
            $data['amount'],
            $data['status']
        ]);
    }

    // Get total revenue
    public function getTotalRevenue()
    {
        $stmt = $this->db->query(
            "SELECT SUM(amount) as total FROM payments WHERE status = 'success'"
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Get recent payments
    public function getRecentPayments($limit = 10)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM payments ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get revenue by date range
    public function getRevenueByDateRange($start, $end)
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) as total 
             FROM payments 
             WHERE status='success' AND DATE(created_at) BETWEEN ? AND ?"
        );
        $stmt->execute([$start, $end]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Get payments by date range
    public function getPaymentsByDateRange($start, $end)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM payments 
             WHERE DATE(created_at) BETWEEN ? AND ? 
             ORDER BY created_at DESC"
        );
        $stmt->execute([$start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
