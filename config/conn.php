<?php
require_once __DIR__ . '/../vendor/autoload.php';
// require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate or retrieve the current CSRF token.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate submitted CSRF token using timing-attack safe comparison.
 */
function verifyCsrfToken(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

$dotEnv = Dotenv::createImmutable(__DIR__ . '/..');
$dotEnv->load();

use models\Config\Conn;

// Define log directory path
$logDir = __DIR__ . '/../logs';

// Create the directory if it doesn't exist
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Direct PHP error logs to revamped error log file
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/app.log');

// Reusable custom logging function
/**
 * Logs a message to the app.log file with a timestamp.
 *
 * @param string $message The message to log.
 */
function logProjectError($message)
{
    $logFile = __DIR__ . '/../logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
}

function connection()

{
    $dbHost     = $_ENV['DB_HOST']     ?? null;
    $dbUser     = $_ENV['DB_USER']     ?? null;
    $dbPassword = $_ENV['PASSWORD']    ?? null;   
    $dbName     = $_ENV['DB_NAME']     ?? null;

    if (!$dbHost || !$dbUser || !$dbName) {
        logProjectError('Missing database credentials in .env');
        return null;
    }
    $conn = new Conn($dbHost,  $dbUser,  $dbPassword,  $dbName);
    if ($conn) {
        return   $conn->getConnect();
    }
    return null;
}

$GLOBALS['connection'] = connection();
