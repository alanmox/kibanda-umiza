<?php
require_once __DIR__ . '/BaseModel.php';

class Match extends BaseModel
{
    private $id;
    private $team_a;
    private $team_b;
    private $competition;
    private $match_date;
    private $match_time;
    private $ticket_price;
    private $total_seats;
    private $status;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'matches';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTeamA($team_a)
    {
        $this->team_a = htmlspecialchars(strip_tags(trim($team_a)));
    }

    public function setTeamB($team_b)
    {
        $this->team_b = htmlspecialchars(strip_tags(trim($team_b)));
    }

    public function setCompetition($competition)
    {
        $this->competition = htmlspecialchars(strip_tags(trim($competition)));
    }

    public function setMatchDate($match_date)
    {
        $this->match_date = htmlspecialchars(strip_tags(trim($match_date)));
    }

    public function setMatchTime($match_time)
    {
        $this->match_time = htmlspecialchars(strip_tags(trim($match_time)));
    }

    public function setTicketPrice($ticket_price)
    {
        $this->ticket_price = floatval($ticket_price);
    }

    public function setTotalSeats($total_seats)
    {
        $this->total_seats = intval($total_seats);
    }

    public function setStatus($status)
    {
        $allowed = ['upcoming', 'ongoing', 'completed'];
        $this->status = in_array($status, $allowed) ? $status : 'upcoming';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTeamA()
    {
        return $this->team_a;
    }

    public function getTeamB()
    {
        return $this->team_b;
    }

    public function getCompetition()
    {
        return $this->competition;
    }

    public function getMatchDate()
    {
        return $this->match_date;
    }

    public function getMatchTime()
    {
        return $this->match_time;
    }

    public function getTicketPrice()
    {
        return $this->ticket_price;
    }

    public function getTotalSeats()
    {
        return $this->total_seats;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->team_a)) {
            $this->errors[] = 'Team A is required.';
        }
        if (empty($this->team_b)) {
            $this->errors[] = 'Team B is required.';
        }
        if (empty($this->competition)) {
            $this->errors[] = 'Competition is required.';
        }
        if (empty($this->match_date)) {
            $this->errors[] = 'Match date is required.';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->match_date)) {
            $this->errors[] = 'Invalid date format. Use YYYY-MM-DD.';
        }
        if (empty($this->match_time)) {
            $this->errors[] = 'Match time is required.';
        } elseif (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $this->match_time)) {
            $this->errors[] = 'Invalid time format. Use HH:MM:SS.';
        }
        if ($this->ticket_price <= 0) {
            $this->errors[] = 'Ticket price must be greater than zero.';
        }
        if ($this->total_seats <= 0) {
            $this->errors[] = 'Total seats must be greater than zero.';
        }

        return empty($this->errors);
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (team_a, team_b, competition, match_date, match_time, ticket_price, total_seats, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $this->team_a,
            $this->team_b,
            $this->competition,
            $this->match_date,
            $this->match_time,
            $this->ticket_price,
            $this->total_seats,
            $this->status ?? 'upcoming',
        ]);
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET team_a = ?, team_b = ?, competition = ?, match_date = ?,
             match_time = ?, ticket_price = ?, total_seats = ?, status = ? WHERE id = ?"
        );
        return $stmt->execute([
            $this->team_a,
            $this->team_b,
            $this->competition,
            $this->match_date,
            $this->match_time,
            $this->ticket_price,
            $this->total_seats,
            $this->status,
            $this->id,
        ]);
    }

    public function search($keyword)
    {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE team_a LIKE ? OR team_b LIKE ? OR competition LIKE ?
             ORDER BY match_date DESC"
        );
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    public function getTodayMatches()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE match_date = CURDATE() ORDER BY match_time ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUpcomingMatches()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE match_date >= CURDATE() ORDER BY match_date ASC, match_time ASC LIMIT 10"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBookedSeats($matchId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as booked FROM customers WHERE match_id = ?"
        );
        $stmt->execute([$matchId]);
        $result = $stmt->fetch();
        return $result['booked'];
    }

    public function getAvailableSeats($matchId)
    {
        $match = $this->read($matchId);
        if (!$match) {
            return 0;
        }
        $booked = $this->getBookedSeats($matchId);
        return $match['total_seats'] - $booked;
    }

    public function getTotalMatchesCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function loadFromArray($data)
    {
        $this->id           = $data['id'] ?? null;
        $this->team_a       = $data['team_a'] ?? '';
        $this->team_b       = $data['team_b'] ?? '';
        $this->competition  = $data['competition'] ?? '';
        $this->match_date   = $data['match_date'] ?? '';
        $this->match_time   = $data['match_time'] ?? '';
        $this->ticket_price = $data['ticket_price'] ?? 0;
        $this->total_seats  = $data['total_seats'] ?? 50;
        $this->status       = $data['status'] ?? 'upcoming';
    }
}
