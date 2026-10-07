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
