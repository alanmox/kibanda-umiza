<?php
require_once __DIR__ . '/BaseModel.php';

class Payment extends BaseModel
{
    private $id;
    private $customer_id;
    private $match_id;
    private $amount_paid;
    private $payment_date;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'payments';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = intval($customer_id);
    }

    public function setMatchId($match_id)
    {
        $this->match_id = intval($match_id);
    }

    public function setAmountPaid($amount_paid)
    {
        $this->amount_paid = floatval($amount_paid);
    }

    public function setPaymentDate($payment_date)
    {
        $this->payment_date = htmlspecialchars(strip_tags(trim($payment_date)));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function getMatchId()
    {
        return $this->match_id;
    }

    public function getAmountPaid()
    {
        return $this->amount_paid;
    }

    public function getPaymentDate()
    {
        return $this->payment_date;
    }

    public function validate()
    {
        $this->errors = [];

        if ($this->customer_id <= 0) {
            $this->errors[] = 'Customer selection is required.';
        }
        if ($this->match_id <= 0) {
            $this->errors[] = 'Match selection is required.';
        }
        if ($this->amount_paid <= 0) {
            $this->errors[] = 'Amount paid must be greater than zero.';
        }
        if (empty($this->payment_date)) {
            $this->errors[] = 'Payment date is required.';
        }

        return empty($this->errors);
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (customer_id, match_id, amount_paid, payment_date)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $this->customer_id,
            $this->match_id,
            $this->amount_paid,
            $this->payment_date,
        ]);
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET customer_id = ?, match_id = ?, amount_paid = ?, payment_date = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $this->customer_id,
            $this->match_id,
            $this->amount_paid,
            $this->payment_date,
            $this->id,
        ]);
    }

    public function getAllWithDetails()
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.ticket_number, c.seat_number, m.team_a, m.team_b, m.match_date
             FROM {$this->table} p
             JOIN customers c ON p.customer_id = c.id
             JOIN matches m ON p.match_id = m.id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute();
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

    public function getPaymentsByDate($date)
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.ticket_number, c.seat_number, m.team_a, m.team_b, m.match_date
             FROM {$this->table} p
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

    public function getTodayRevenue()
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(amount_paid), 0) as total FROM {$this->table}
             WHERE payment_date = CURDATE()"
        );
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getRevenueByDate($date)
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(amount_paid), 0) as total FROM {$this->table}
             WHERE payment_date = ?"
        );
        $stmt->execute([$date]);
        return $stmt->fetch()['total'];
    }

    public function getTotalRevenue()
    {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount_paid), 0) as total FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getTotalPaymentsCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function loadFromArray($data)
    {
        $this->id           = $data['id'] ?? null;
        $this->customer_id  = $data['customer_id'] ?? 0;
        $this->match_id     = $data['match_id'] ?? 0;
        $this->amount_paid  = $data['amount_paid'] ?? 0;
        $this->payment_date = $data['payment_date'] ?? date('Y-m-d');
    }
}
