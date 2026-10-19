<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';

use Models\Admin\Authentication;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function register()
{
    $connection = $GLOBALS['connection'] ?? null;

    if (!$connection) {
        logProjectError("Controller Error: \$connection is null or invalid.");
        $_SESSION['error_message'] = "Database connection failed. Check logs.";
        header("Location: /revamp/admin/register");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        $_SESSION['error_message'] = 'Request Denied';
        header("Location: /revamp/admin/register");
        exit;
    }
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        logProjectError("CSRF Verification Failed: Invalid or missing token.");
        http_response_code(403);
        die("Invalid security token. Please refresh the page and try again.");
    }
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pswd = trim($_POST['password'] ?? '');


    if (empty($name) || empty($pswd)) {
        $_SESSION['error_message'] = "Username and password are required.";
        header("Location: /revamp/admin/register");
        exit;
    }


    $auth = new Authentication($name, $pswd, $connection);

    if ($auth->register()) {
        header("Location: /revamp/admin/login");
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed. Username may already exist.";
        header("Location: /revamp/admin/register");
        exit;
    }
}

function login()
{
    // RATE LIMIT
    $maxAttempt = 5;
    $coolOff = 300;
    $connection = $GLOBALS['connection'] ?? null;


    if (!$connection) {
        logProjectError("Controller Error: \$connection is null or invalid.");
        $_SESSION['error_message'] = "Database connection failed. Check logs.";
        header("Location: /revamp/admin/login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        $_SESSION['error_message'] = 'Request Denied';
        header("Location: /revamp/admin/login");
        exit;
    }
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        logProjectError("CSRF Verification Failed: Invalid or missing token.");
        http_response_code(403);
        die("Invalid security token. Please refresh the page and try again.");
    }

    $attempts = $_SESSION['Login_Attempts'] ?? 0;
    $lastTime = $_SESSION['Last_attempt_time'] ?? 0;


    if ($attempts >= $maxAttempt) {
        $elapsed = time() - $lastTime;
        if ($elapsed < $coolOff) {
            $minutes = (int) ceil(($coolOff - $elapsed) / 60);
            $_SESSION['error_message'] = "Too many failed attempts. Try again in $minutes minute(s).";
            header("Location: /revamp/admin/login");
            exit;
        }
        $attempts = 0; // lockout expired
        $_SESSION['Login_Attempts'] = 0;
    }


    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pswd = trim($_POST['password'] ?? '');

    if (empty($name) || empty($pswd)) {
        $_SESSION['error_message'] = "Username and password are required.";
        header("Location: /revamp/admin/login");
        exit;
    }


    $auth = new Authentication($name, $pswd, $connection);
    if ($auth->login()) {
        session_regenerate_id(true);
        unset($_SESSION['Login_Attempts'], $_SESSION['Last_attempt_time']);
        header("Location: /revamp/admin");
        exit;
    }
    // Record the failure
    $_SESSION['Login_Attempts']    = $attempts + 1;
    $_SESSION['Last_attempt_time'] = time();
    $attemptsLeft = $maxAttempt - $_SESSION['Login_Attempts'];
    $_SESSION['error_message'] = "Invalid username or password," . $attemptsLeft . " attempt(s) left.";
    header("Location: /revamp/admin/login");
    exit;
}

function logout()
{
    // 1. Ensure session is active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 2. Unset all session variables
    $_SESSION = [];

    // 3. Delete the session cookie from the browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 4. Destroy the session
    session_destroy();

    // 5. Redirect to login page with a status message
    header("Location: /revamp/admin/login");
    exit;
}
