<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Encryption.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/FootballMatch.php';
require_once __DIR__ . '/../classes/Customer.php';
require_once __DIR__ . '/../classes/Payment.php';
require_once __DIR__ . '/../classes/Report.php';

$auth         = new Auth();
$footballMatch = new FootballMatch();
$customer     = new Customer();
$payment      = new Payment();
$report       = new Report();

$page     = $_GET['page'] ?? 'landing';
$action   = $_GET['action'] ?? '';
$id       = $_GET['id'] ?? null;

ob_start();

switch ($page) {
    case 'landing':
        $todayMatches    = $footballMatch->getTodayMatches();
        $upcomingMatches = $footballMatch->getUpcomingMatches();

        $bookingMessage = '';
        $bookingError   = '';
        $bookingLookup  = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['register_booking'])) {
                $customer->setFullName($_POST['full_name'] ?? '');
                $customer->setPhone($_POST['phone'] ?? '');
                $customer->setGender($_POST['gender'] ?? '');
                $customer->setSeatNumber($_POST['seat_number'] ?? 0);
                $customer->setMatchId($_POST['match_id'] ?? 0);
                if ($auth->isLoggedIn()) {
                    $customer->setUserId($_SESSION['user_id']);
                }

                if ($customer->create()) {
                    $selectedMatch = $footballMatch->read($customer->getMatchId());
                    $paymentOption = $_POST['payment_option'] ?? 'reserve';

                    if ($paymentOption === 'pay_now' && $selectedMatch) {
                        $payment->setCustomerId($customer->getId());
                        $payment->setMatchId($customer->getMatchId());
                        $payment->setAmountPaid($selectedMatch['ticket_price'] ?? 0);
                        $payment->setPaymentDate(date('Y-m-d'));

                        if ($payment->create()) {
                            $bookingMessage = 'Booking confirmed successfully! Ticket #' . $customer->getTicketNumber() . '. Payment recorded.';
                        } else {
                            $bookingMessage = 'Booking confirmed successfully! Ticket #' . $customer->getTicketNumber() . '. Payment could not be recorded.';
                        }
                    } else {
                        $bookingMessage = 'Booking reserved successfully! Ticket #' . $customer->getTicketNumber() . '. Please pay at the counter.';
                    }
                } else {
                    $bookingError = 'Booking failed: ' . implode(', ', $customer->getErrors());
                }
            } elseif (isset($_POST['lookup_ticket'])) {
                $ticketNumber = trim($_POST['ticket_number'] ?? '');
                if ($ticketNumber !== '') {
                    $bookingLookup = $customer->getCustomerByTicketNumber($ticketNumber);
                    if (!$bookingLookup) {
                        $bookingError = 'No booking found for that ticket number.';
                    }
                } else {
                    $bookingError = 'Please enter a ticket number.';
                }
            }
        }

        include __DIR__ . '/../views/landing.php';
        break;

    case 'auth':
        switch ($action) {
            case 'login':
                include __DIR__ . '/../views/auth/login.php';
                break;

            case 'do_login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    if ($auth->login($username, $password)) {
                        $role = $auth->getRole();
                        if ($role === 'admin') {
                            header('Location: ?page=admin&action=dashboard');
                        } else {
                            header('Location: ?page=landing');
                        }
                        exit;
                    } else {
                        $loginError = 'Invalid username or password.';
                        include __DIR__ . '/../views/auth/login.php';
                    }
                } else {
                    header('Location: ?page=auth&action=login');
                    exit;
                }
                break;

            case 'register':
                include __DIR__ . '/../views/auth/register.php';
                break;

            case 'do_register':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username         = $_POST['username'] ?? '';
                    $email            = $_POST['email'] ?? '';
                    $password         = $_POST['password'] ?? '';
                    $confirmPassword  = $_POST['confirm_password'] ?? '';

                    if ($password !== $confirmPassword) {
                        $registerError = 'Passwords do not match.';
                        include __DIR__ . '/../views/auth/register.php';
                        break;
                    }

                    $result   = $auth->register($username, $email, $password);
                    if ($result === true) {
                        $registerSuccess = 'Registration successful! You can now log in.';
                        include __DIR__ . '/../views/auth/login.php';
                    } else {
                        $registerError = $result;
                        include __DIR__ . '/../views/auth/register.php';
                    }
                } else {
                    header('Location: ?page=auth&action=register');
                    exit;
                }
                break;

            case 'logout':
                $auth->logout();
                header('Location: ?page=landing');
                exit;

            default:
                header('Location: ?page=auth&action=login');
                exit;
        }
        break;

    case 'user':
        if (!$auth->isLoggedIn()) {
            header('Location: ?page=auth&action=login');
            exit;
        }

        switch ($action) {
            case 'dashboard':
                $user        = $auth->getCurrentUser();
                $bookings    = $customer->getByUserId($user['id']);
                $totalSpent  = 0;
                foreach ($bookings as $b) {
                    $totalSpent += floatval($b['ticket_price'] ?? 0);
                }
                include __DIR__ . '/../views/user/dashboard.php';
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
                $user     = $auth->getCurrentUser();
                $bookings = $customer->getByUserId($user['id']);
                $totalSpent  = 0;
                foreach ($bookings as $b) {
                    $totalSpent += floatval($b['ticket_price'] ?? 0);
                }
                include __DIR__ . '/../views/user/dashboard.php';
                break;

            default:
                header('Location: ?page=user&action=dashboard');
                exit;
        }
        break;

    case 'admin':
        if (!$auth->isAdmin()) {
            header('Location: ?page=auth&action=login');
            exit;
        }

        switch ($action) {

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
                $totalMatches     = $footballMatch->getTotalMatchesCount();
                $todayCustomers   = $customer->getTodayCount();
                $todayRevenue     = $payment->getTodayRevenue();
                $allMatches       = $footballMatch->getAll();
                $upcomingMatches  = $footballMatch->getUpcomingMatches();
                $totalUsers       = $auth->getTotalUsersCount();
                include __DIR__ . '/../views/admin/dashboard.php';
                break;

            case 'matches':
                $successMessage = '';
                $errorMessage   = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['add_match'])) {
                        $footballMatch->loadFromArray($_POST);
                        if ($footballMatch->create()) {
                            $successMessage = 'Match added successfully.';
                        } else {
                            $errorMessage = 'Failed to add match: ' . $footballMatch->getLastError();
                        }
                    } elseif (isset($_POST['edit_match'])) {
                        $footballMatch->loadFromArray($_POST);
                        $footballMatch->setId($_POST['id']);
                        if ($footballMatch->update()) {
                            $successMessage = 'Match updated successfully.';
                        } else {
                            $errorMessage = 'Failed to update match: ' . $footballMatch->getLastError();
                        }
                    } elseif (isset($_POST['delete_match'])) {
                        if ($footballMatch->delete($_POST['id'])) {
                            $successMessage = 'Match deleted successfully.';
                        } else {
                            $errorMessage = 'Failed to delete match.';
                        }
                    } elseif (isset($_POST['search'])) {
                        $footballMatches = $footballMatch->search($_POST['keyword']);
                    }
                }

                if (!isset($footballMatches)) {
                    $footballMatches = $footballMatch->getActiveMatches();
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
                $allMatches    = $footballMatch->getAll();
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
                $allMatches    = $footballMatch->getAll();
                include __DIR__ . '/../views/admin/payments.php';
                break;

            case 'users':
                $users = $auth->getAllUsersWithBookingCounts();
                include __DIR__ . '/../views/admin/users.php';
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
                header('Location: ?page=admin&action=dashboard');
                exit;
        }
        break;

    default:
        $todayMatches    = $footballMatch->getTodayMatches();
        $upcomingMatches = $footballMatch->getUpcomingMatches();
        include __DIR__ . '/../views/landing.php';
        break;
}

$content = ob_get_clean();
echo $content;
