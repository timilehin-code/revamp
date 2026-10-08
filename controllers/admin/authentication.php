<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';

use models\admin\Authentication;

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

    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pswd = trim($_POST['password'] ?? '');

    if (empty($name) || empty($pswd)) {
        $_SESSION['error_message'] = "Username and password are required.";
        header("Location: /revamp/admin/login");
        exit;
    }


    $auth = new Authentication($name, $pswd, $connection);
    $user = $auth->login();

    if ($user) {
        header("Location: /revamp/admin");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid username or password.";
        header("Location: /revamp/admin/login");
        exit;
    }
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
