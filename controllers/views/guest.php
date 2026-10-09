<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';

use models\views\Guests;
use models\Views\ViewGuests;

function saveGuestNote()
{
    $connection = $GLOBALS['connection'] ?? null;

    if (!$connection) {
        logProjectError("Controller Error: \$connection is null or invalid.");
        $_SESSION['error_message'] = "Database connection failed. Check logs.";
        header("Location: /revamp/guests");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        $_SESSION['error_message'] = 'Request Denied';
        header("Location: /revamp/guests");
        exit;
    }
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($token)) {
        logProjectError("CSRF Verification Failed: Invalid or missing token.");
        http_response_code(403);
        die("Invalid security token. Please refresh the page and try again.");
    }
    $fullName = trim($_POST['fullName'] ?? '');
    $shortNote = trim($_POST['shortNote'] ?? '');
    $base64Image = $_POST['signature_data'] ?? '';
    // Basic server-side validation
    if (empty($fullName) || empty($shortNote) || empty($base64Image)) {
        die("Error: All fields including signature are required.");
    }

    if (strpos($base64Image, 'data:image/png;base64,') !== 0) {
        die("Error: Invalid image payload format.");
    }
    $save = new Guests($fullName, $shortNote, $base64Image, $connection);
    if ($save->saveGuest()) {
        header("Location: /revamp/guests");
        exit;
    }
}
$connection = $GLOBALS['connection'] ?? null;

if ($connection) {
    $viewGuests = new ViewGuests($connection);
    $allGuests = $viewGuests->getAllGuests();
}
