<?php
class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['logged_in']   = true;
            return true;
        }
        return false;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout()
    {
        session_destroy();
        return true;
    }

    public function changePassword($currentPassword, $newPassword)
    {
        $adminId = $_SESSION['admin_id'];
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
            return 'Current password is incorrect.';
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $adminId]);
        return true;
    }

    public function getCurrentAdmin()
    {
        if ($this->isLoggedIn()) {
            return [
                'id'       => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username'],
            ];
        }
        return null;
    }
}
