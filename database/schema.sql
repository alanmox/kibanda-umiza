-- ============================================================
-- Kibanda Umiza - Football Viewing Center Management System
-- Database Schema - Third Normal Form (3NF)
-- ============================================================

CREATE DATABASE IF NOT EXISTS kibanda_umiza;
USE kibanda_umiza;

-- ------------------------------------------------------------
-- 1. admins table
--    Stores system admin credentials
-- ------------------------------------------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. matches table
--    Stores football match details
--    PK: id
-- ------------------------------------------------------------
CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_a VARCHAR(100) NOT NULL,
    team_b VARCHAR(100) NOT NULL,
    competition VARCHAR(100) NOT NULL,
    match_date DATE NOT NULL,
    match_time TIME NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL DEFAULT 50,
    status ENUM('upcoming', 'ongoing', 'completed') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. customers table
--    Stores customer registrations with encrypted PII
--    PK: id
--    FK: match_id REFERENCES matches(id)
--    UK: ticket_number (unique 6-digit)
--    UK: (match_id, seat_number) prevents duplicate seats
-- ------------------------------------------------------------
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name_encrypted VARCHAR(255) NOT NULL,
    phone_encrypted VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    ticket_number VARCHAR(6) NOT NULL UNIQUE,
    seat_number INT NOT NULL,
    match_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat_per_match (match_id, seat_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. payments table
--    Stores cash payment records
--    PK: id
--    FK: customer_id REFERENCES customers(id)
--    FK: match_id REFERENCES matches(id)
-- ------------------------------------------------------------
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    match_id INT NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Default admin account (password: admin123)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample matches
INSERT INTO matches (team_a, team_b, competition, match_date, match_time, ticket_price, total_seats, status) VALUES
('Liverpool', 'Manchester City', 'English Premier League', '2026-06-17', '20:00:00', 15.00, 50, 'upcoming'),
('Barcelona', 'Real Madrid', 'La Liga', '2026-06-18', '21:00:00', 20.00, 50, 'upcoming'),
('Juventus', 'AC Milan', 'Serie A', '2026-06-19', '19:30:00', 12.00, 50, 'upcoming'),
('Bayern Munich', 'Borussia Dortmund', 'Bundesliga', '2026-06-20', '20:00:00', 18.00, 50, 'upcoming'),
('Arsenal', 'Chelsea', 'English Premier League', '2026-06-17', '18:00:00', 15.00, 50, 'upcoming'),
('Paris Saint-Germain', 'Marseille', 'Ligue 1', '2026-06-21', '21:00:00', 14.00, 50, 'upcoming'),
('Manchester United', 'Tottenham', 'English Premier League', '2026-06-22', '20:00:00', 16.00, 50, 'upcoming'),
('Inter Milan', 'Napoli', 'Serie A', '2026-06-23', '19:45:00', 13.00, 50, 'upcoming');
