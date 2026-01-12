<?php
class Finance
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function summary()
    {
        $this->db->query("
            SELECT 
                COUNT(*) AS total_transactions,
                SUM(amount) AS total_revenue
            FROM payments
            WHERE status = 'success'
        ");
        return $this->db->single();
    }

    public function today()
    {
        $this->db->query("
            SELECT 
                SUM(amount) AS revenue_today
            FROM payments
            WHERE status = 'success'
              AND DATE(created_at) = CURDATE()
        ");
        return $this->db->single();
    }

    public function byDateRange($start, $end)
    {
        $this->db->query("
            SELECT * FROM payments
            WHERE status = 'success'
              AND DATE(created_at) BETWEEN :start AND :end
            ORDER BY created_at DESC
        ");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        return $this->db->resultSet();
    }
}
