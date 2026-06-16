<?php
class Report
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getDailyCustomers($date)
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, m.team_a, m.team_b, m.competition, m.match_date as match_date_display, p.amount_paid
             FROM customers c
             JOIN matches m ON c.match_id = m.id
             LEFT JOIN payments p ON c.id = p.customer_id
             WHERE DATE(c.created_at) = ?
             ORDER BY c.created_at DESC"
        );
        $stmt->execute([$date]);
        $customers = $stmt->fetchAll();

        foreach ($customers as &$customer) {
            $customer['full_name'] = Encryption::decrypt($customer['full_name_encrypted']);
            $customer['phone']     = Encryption::decrypt($customer['phone_encrypted']);
        }

        return $customers;
    }

    public function getDailyRevenue($date)
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.ticket_number, c.seat_number,
                    m.team_a, m.team_b, m.competition, m.match_date as match_date_display
             FROM payments p
             JOIN customers c ON p.customer_id = c.id
             JOIN matches m ON p.match_id = m.id
             WHERE p.payment_date = ?
             ORDER BY p.created_at DESC"
        );
        $stmt->execute([$date]);
        $payments = $stmt->fetchAll();

        foreach ($payments as &$payment) {
            $customerStmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
            $customerStmt->execute([$payment['customer_id']]);
            $customer = $customerStmt->fetch();
            if ($customer) {
                $payment['customer_name'] = Encryption::decrypt($customer['full_name_encrypted']);
            }
        }

        return $payments;
    }

    public function getTotalRevenueByDate($date)
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(amount_paid), 0) as total
             FROM payments WHERE payment_date = ?"
        );
        $stmt->execute([$date]);
        return $stmt->fetch()['total'];
    }

    public function getCustomerCountByDate($date)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM customers WHERE DATE(created_at) = ?"
        );
        $stmt->execute([$date]);
        return $stmt->fetch()['total'];
    }

    public function getDateRangeReport($startDate, $endDate)
    {
        $stmt = $this->db->prepare(
            "SELECT p.payment_date,
                    COUNT(DISTINCT p.customer_id) as customer_count,
                    SUM(p.amount_paid) as total_revenue
             FROM payments p
             WHERE p.payment_date BETWEEN ? AND ?
             GROUP BY p.payment_date
             ORDER BY p.payment_date DESC"
        );
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getMatchReport($matchId)
    {
        $stmt = $this->db->prepare(
            "SELECT m.*,
                    COUNT(DISTINCT c.id) as total_customers,
                    COALESCE(SUM(p.amount_paid), 0) as total_revenue
             FROM matches m
             LEFT JOIN customers c ON m.id = c.match_id
             LEFT JOIN payments p ON c.id = p.customer_id
             WHERE m.id = ?
             GROUP BY m.id"
        );
        $stmt->execute([$matchId]);
        return $stmt->fetch();
    }
}
