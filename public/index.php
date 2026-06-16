<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Encryption.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Match.php';
require_once __DIR__ . '/../classes/Customer.php';
require_once __DIR__ . '/../classes/Payment.php';
require_once __DIR__ . '/../classes/Report.php';

$auth   = new Auth();
$match  = new Match();
$customer = new Customer();
$payment  = new Payment();
$report   = new Report();

$page     = $_GET['page'] ?? 'landing';
$action   = $_GET['action'] ?? '';
$id       = $_GET['id'] ?? null;

ob_start();

switch ($page) {
    case 'landing':
        $todayMatches    = $match->getTodayMatches();
        $upcomingMatches = $match->getUpcomingMatches();
        include __DIR__ . '/../views/landing.php';
        break;

    case 'admin':
        if (!$auth->isLoggedIn() && $action !== 'login' && $action !== 'do_login') {
            $action = 'login';
        }

        switch ($action) {
            case 'login':
                include __DIR__ . '/../views/admin/login.php';
                break;

            case 'do_login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    if ($auth->login($username, $password)) {
                        header('Location: ?page=admin&action=dashboard');
                        exit;
                    } else {
                        $loginError = 'Invalid username or password.';
                        include __DIR__ . '/../views/admin/login.php';
                    }
                } else {
                    header('Location: ?page=admin&action=login');
                    exit;
                }
                break;

            case 'logout':
                $auth->logout();
                header('Location: ?page=landing');
                exit;
                break;

            case 'change_password':
                $passwordMessage = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $auth->changePassword(
                        $_POST['current_password'] ?? '',
                        $_POST['new_password'] ?? ''
                    );
                    if ($result === true) {
                        $passwordMessage = 'Password changed successfully.';
                    } else {
                        $passwordMessage = $result;
                    }
                }
                include __DIR__ . '/../views/admin/change_password.php';
                break;

            case 'dashboard':
                $totalMatches     = $match->getTotalMatchesCount();
                $todayCustomers   = $customer->getTodayCount();
                $todayRevenue     = $payment->getTodayRevenue();
                $allMatches       = $match->getAll();
                $upcomingMatches  = $match->getUpcomingMatches();
                include __DIR__ . '/../views/admin/dashboard.php';
                break;

            case 'matches':
                $successMessage = '';
                $errorMessage   = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['add_match'])) {
                        $match->loadFromArray($_POST);
                        if ($match->create()) {
                            $successMessage = 'Match added successfully.';
                        } else {
                            $errorMessage = 'Failed to add match: ' . $match->getLastError();
                        }
                    } elseif (isset($_POST['edit_match'])) {
                        $match->loadFromArray($_POST);
                        $match->setId($_POST['id']);
                        if ($match->update()) {
                            $successMessage = 'Match updated successfully.';
                        } else {
                            $errorMessage = 'Failed to update match: ' . $match->getLastError();
                        }
                    } elseif (isset($_POST['delete_match'])) {
                        if ($match->delete($_POST['id'])) {
                            $successMessage = 'Match deleted successfully.';
                        } else {
                            $errorMessage = 'Failed to delete match.';
                        }
                    } elseif (isset($_POST['search'])) {
                        $matches = $match->search($_POST['keyword']);
                    }
                }

                if (!isset($matches)) {
                    $matches = $match->getAll();
                }

                include __DIR__ . '/../views/admin/matches.php';
                break;

            case 'customers':
                $successMessage = '';
                $errorMessage   = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_customer'])) {
                    $customer->setFullName($_POST['full_name'] ?? '');
                    $customer->setPhone($_POST['phone'] ?? '');
                    $customer->setGender($_POST['gender'] ?? '');
                    $customer->setSeatNumber($_POST['seat_number'] ?? 0);
                    $customer->setMatchId($_POST['match_id'] ?? 0);

                    if ($customer->create()) {
                        $successMessage = 'Customer registered successfully! Ticket #: ' . $customer->getTicketNumber();
                    } else {
                        $errorMessage = 'Registration failed: ' . implode(', ', $customer->getErrors());
                    }
                }

                $customers     = $customer->getAll();
                $customerMatch = isset($_GET['match_id']) ? $customer->getCustomersByMatch($_GET['match_id']) : null;
                $allMatches    = $match->getAll();
                include __DIR__ . '/../views/admin/customers.php';
                break;

            case 'payments':
                $successMessage = '';
                $errorMessage   = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['record_payment'])) {
                    $payment->setCustomerId($_POST['customer_id'] ?? 0);
                    $payment->setMatchId($_POST['match_id'] ?? 0);
                    $payment->setAmountPaid($_POST['amount_paid'] ?? 0);
                    $payment->setPaymentDate($_POST['payment_date'] ?? date('Y-m-d'));

                    if ($payment->create()) {
                        $successMessage = 'Payment recorded successfully.';
                    } else {
                        $errorMessage = 'Payment failed: ' . implode(', ', $payment->getErrors());
                    }
                }

                $payments      = $payment->getAllWithDetails();
                $allCustomers  = $customer->getAll();
                $allMatches    = $match->getAll();
                include __DIR__ . '/../views/admin/payments.php';
                break;

            case 'reports':
                $reportDate    = $_GET['date'] ?? date('Y-m-d');
                $dailyCustomers = $report->getDailyCustomers($reportDate);
                $dailyRevenue   = $report->getDailyRevenue($reportDate);
                $totalRevenue   = $report->getTotalRevenueByDate($reportDate);
                $customerCount  = $report->getCustomerCountByDate($reportDate);
                include __DIR__ . '/../views/admin/reports.php';
                break;

            default:
                include __DIR__ . '/../views/admin/dashboard.php';
                break;
        }
        break;

    default:
        $todayMatches    = $match->getTodayMatches();
        $upcomingMatches = $match->getUpcomingMatches();
        include __DIR__ . '/../views/landing.php';
        break;
}

$content = ob_get_clean();
echo $content;
