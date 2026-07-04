<?php
require_once __DIR__ . '/BaseModel.php';

class Customer extends BaseModel
{
    private $id;
    private $full_name;
    private $phone;
    private $gender;
    private $ticket_number;
    private $seat_number;
    private $match_id;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'customers';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFullName($full_name)
    {
        $this->full_name = htmlspecialchars(strip_tags(trim($full_name)));
    }

    public function setPhone($phone)
    {
        $this->phone = htmlspecialchars(strip_tags(trim($phone)));
    }

    public function setGender($gender)
    {
        $allowed = ['Male', 'Female', 'Other'];
        $this->gender = in_array($gender, $allowed) ? $gender : 'Other';
    }

    public function setTicketNumber($ticket_number)
    {
        $this->ticket_number = $ticket_number;
    }

    public function setSeatNumber($seat_number)
    {
        $this->seat_number = intval($seat_number);
    }

    public function setMatchId($match_id)
    {
        $this->match_id = intval($match_id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getTicketNumber()
    {
        return $this->ticket_number;
    }

    public function getSeatNumber()
    {
        return $this->seat_number;
    }

    public function getMatchId()
    {
        return $this->match_id;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->full_name)) {
            $this->errors[] = 'Full name is required.';
        } elseif (strlen($this->full_name) < 3) {
            $this->errors[] = 'Name must be at least 3 characters.';
        }

        if (empty($this->phone)) {
            $this->errors[] = 'Phone number is required.';
        } elseif (!preg_match('/^[0-9+\-\s()]{7,15}$/', $this->phone)) {
            $this->errors[] = 'Invalid phone number format.';
        }

        if (empty($this->gender)) {
            $this->errors[] = 'Gender is required.';
        }

        if ($this->seat_number <= 0) {
            $this->errors[] = 'Seat number must be greater than zero.';
        }

        if ($this->match_id <= 0) {
            $this->errors[] = 'Match selection is required.';
        } else {
            $matchStmt = $this->db->prepare('SELECT total_seats FROM matches WHERE id = ?');
            $matchStmt->execute([$this->match_id]);
            $match = $matchStmt->fetch();

            if (!$match) {
                $this->errors[] = 'Selected match was not found.';
            } elseif ($this->seat_number > intval($match['total_seats'])) {
                $this->errors[] = 'Seat number exceeds the total seats for this match.';
            }
        }

        if ($this->isSeatTaken()) {
            $this->errors[] = "Seat number {$this->seat_number} is already taken for this match.";
        }

        return empty($this->errors);
    }

    public function getCustomerByTicketNumber($ticketNumber)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE ticket_number = ?");
        $stmt->execute([$ticketNumber]);
        $customer = $stmt->fetch();

        if ($customer) {
            $customer['full_name'] = Encryption::decrypt($customer['full_name_encrypted']);
            $customer['phone']     = Encryption::decrypt($customer['phone_encrypted']);
        }

        return $customer;
    }

    public function isSeatTaken()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM customers WHERE match_id = ? AND seat_number = ?"
        );
        $stmt->execute([$this->match_id, $this->seat_number]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function generateTicketNumber()
    {
        do {
            $number = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) as count FROM {$this->table} WHERE ticket_number = ?"
            );
            $stmt->execute([$number]);
            $result = $stmt->fetch();
        } while ($result['count'] > 0);

        return $number;
    }

    public function create()
    {
        $this->ticket_number = $this->generateTicketNumber();

        if (!$this->validate()) {
            return false;
        }

        $encryptedName  = Encryption::encrypt($this->full_name);
        $encryptedPhone = Encryption::encrypt($this->phone);

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (full_name_encrypted, phone_encrypted, gender, ticket_number, seat_number, match_id)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([
            $encryptedName,
            $encryptedPhone,
            $this->gender,
            $this->ticket_number,
            $this->seat_number,
            $this->match_id,
        ]);

        if ($result) {
            $this->id = $this->db->lastInsertId();
        }

        return $result;
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        $encryptedName  = Encryption::encrypt($this->full_name);
        $encryptedPhone = Encryption::encrypt($this->phone);

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET full_name_encrypted = ?, phone_encrypted = ?, gender = ?,
             seat_number = ?, match_id = ? WHERE id = ?"
        );
        return $stmt->execute([
            $encryptedName,
            $encryptedPhone,
            $this->gender,
            $this->seat_number,
            $this->match_id,
            $this->id,
        ]);
    }

    public function read($id)
    {
        $data = parent::read($id);
        if ($data) {
            $data['full_name'] = Encryption::decrypt($data['full_name_encrypted']);
            $data['phone']     = Encryption::decrypt($data['phone_encrypted']);
        }
        return $data;
    }

    public function getAll()
    {
        $customers = parent::getAll();
        foreach ($customers as &$customer) {
            $customer['full_name'] = Encryption::decrypt($customer['full_name_encrypted']);
            $customer['phone']     = Encryption::decrypt($customer['phone_encrypted']);
        }
        return $customers;
    }

    public function getCustomersByMatch($matchId)
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, m.team_a, m.team_b, m.match_date
             FROM {$this->table} c
             JOIN matches m ON c.match_id = m.id
             WHERE c.match_id = ?
             ORDER BY c.seat_number ASC"
        );
        $stmt->execute([$matchId]);
        $customers = $stmt->fetchAll();
        foreach ($customers as &$customer) {
            $customer['full_name'] = Encryption::decrypt($customer['full_name_encrypted']);
            $customer['phone']     = Encryption::decrypt($customer['phone_encrypted']);
        }
        return $customers;
    }

    public function getCustomersByDate($date)
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, m.team_a, m.team_b, m.match_date
             FROM {$this->table} c
             JOIN matches m ON c.match_id = m.id
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

    public function getTodayCount()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(created_at) = CURDATE()"
        );
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function getTotalCustomersCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function loadFromArray($data)
    {
        $this->id           = $data['id'] ?? null;
        $this->full_name    = $data['full_name'] ?? '';
        $this->phone        = $data['phone'] ?? '';
        $this->gender       = $data['gender'] ?? '';
        $this->ticket_number = $data['ticket_number'] ?? '';
        $this->seat_number  = $data['seat_number'] ?? 0;
        $this->match_id     = $data['match_id'] ?? 0;
    }
}
