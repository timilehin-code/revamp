<?php
require_once __DIR__ . '/../vendor/autoload.php';

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
    $conn = new Conn("localHost", "root", "", "portfolio");
    if ($conn) {
        return   $conn->getConnect();
    }
    return null;
}

$GLOBALS['connection'] = connection();
