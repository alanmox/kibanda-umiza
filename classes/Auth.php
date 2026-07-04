<?php
class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $email, $password)
    {
        if (strlen($username) < 3) {
            return 'Username must be at least 3 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address.';
        }
        if (strlen($password) < 6) {
            return 'Password must be at least 6 characters.';
        }
        if ($this->usernameExists($username)) {
            return 'Username already taken.';
        }
        if ($this->emailExists($email)) {
            return 'Email already registered.';
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$username, $email, $hash]);
        return true;
    }

    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']     = $user['id'];
            $_SESSION['username']    = $user['username'];
            $_SESSION['user_role']   = $user['role'];
            $_SESSION['logged_in']   = true;
            return true;
        }
        return false;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function isAdmin()
    {
        return $this->isLoggedIn()
            && isset($_SESSION['user_role'])
            && $_SESSION['user_role'] === 'admin';
    }

    public function getRole()
    {
        return $_SESSION['user_role'] ?? null;
    }

    public function logout()
    {
        session_destroy();
        return true;
    }

    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id'       => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role'     => $_SESSION['user_role'],
            ];
        }
        return null;
    }

    public function changePassword($currentPassword, $newPassword)
    {
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return 'Current password is incorrect.';
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $userId]);
        return true;
    }

    private function usernameExists($username)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    private function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
