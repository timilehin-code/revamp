<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendMail()
{
    // 1. Guard clause: Ensure request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo 'Method Not Allowed';
        return;
    }

    // 2. Extract & Sanitize Inputs
    $name    = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $subject = filter_var(trim($_POST['subject'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_var(trim($_POST['message'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$email || empty($name) || empty($message)) {
        http_response_code(400);
        echo 'Invalid input or email address provided.';
        return;
    }

    // 3. Load Environment Variables safely
    $host     = $_ENV['EMAIL_HOST'] ?? getenv('EMAIL_HOST');
    $port     = $_ENV['EMAIL_PORT'] ?? getenv('EMAIL_PORT');
    $userName = $_ENV['USER_NAME'] ?? getenv('USER_NAME');
    $password = $_ENV['EMAIL_PASSWORD'] ?? getenv('EMAIL_PASSWORD');

    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Set to DEBUG_OFF for production
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $userName;
        $mail->Password   = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int) $port;

        // --- Sender & Recipient Setup ---
        // Sender MUST be your authenticated SMTP account
        $mail->setFrom($userName, 'Portfolio Contact Form');

        // Recipient is YOUR inbox
        $mail->addAddress($userName, 'Oluwatimilehin');

        // Set visitor's email so clicking "Reply" responds directly to them
        $mail->addReplyTo($email, $name);

        // --- Email Content ---
        $mail->isHTML(true);
        $mail->Subject = "New Form Submission: " . ($subject ?: 'No Subject');
        $mail->Body = '
        <div style="background-color: #1f1f1f; border: 1px solid #343232; border-radius: 12px; padding: 24px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; color: #f1f1f1; max-width: 550px;">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #ffffff; border-bottom: 2px solid #49378f; padding-bottom: 10px;">
                New Message Received
            </h3>
            <p style="margin: 0 0 12px 0; font-size: 14px; color: #a1a1a1;">
                <strong style="color: #8e8e8e; text-transform: uppercase; font-size: 11px; display: inline-block; width: 80px;">Name:</strong>
                <span style="color: #ffffff; font-weight: 600;">' . htmlspecialchars($name) . '</span>
            </p>
            <p style="margin: 0 0 12px 0; font-size: 14px; color: #a1a1a1;">
                <strong style="color: #8e8e8e; text-transform: uppercase; font-size: 11px; display: inline-block; width: 80px;">Email:</strong>
                <a href="mailto:' . htmlspecialchars($email) . '" style="color: #a1a1a1; font-weight: 600; text-decoration: underline;">' . htmlspecialchars($email) . '</a>
            </p>
            <p style="margin: 0 0 20px 0; font-size: 14px; color: #a1a1a1;">
                <strong style="color: #8e8e8e; text-transform: uppercase; font-size: 11px; display: inline-block; width: 80px;">Subject:</strong>
                <span style="color: #f1f1f1; font-weight: 600;">' . htmlspecialchars($subject) . '</span>
            </p>
            <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 700; color: #8e8e8e; text-transform: uppercase;">
                Message:
            </p>
            <div style="background-color: #343232; border-radius: 8px; padding: 16px; color: #f1f1f1; font-size: 14px; line-height: 1.6; border: 1px solid #1f1f1f26;">
                ' . nl2br($message) . '
            </div>
        </div>';

        $mail->send();
        echo 'Message has been sent successfully.';
    } catch (Exception $e) {
        if (function_exists('logProjectError')) {
            logProjectError("Mailer Error: " . $mail->ErrorInfo);
        }
        http_response_code(500);
        echo "Message could not be sent. Please try again later.";
    }

    $mail->send();

    // --- 2. Send Automatic Feedback / Auto-Reply to Visitor ---
    try {
        $autoReply = new PHPMailer(true);
        $autoReply->isSMTP();
        $autoReply->Host       = $host;
        $autoReply->SMTPAuth   = true;
        $autoReply->Username   = $userName;
        $autoReply->Password   = $password;
        $autoReply->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $autoReply->Port       = (int) $port;

        // Sender is you, Recipient is the visitor
        $autoReply->setFrom($userName, 'Oluwatimilehin');
        $autoReply->addAddress($email, $name);

        $autoReply->isHTML(true);
        $autoReply->Subject = "Thanks for reaching out, " . $name . "!";
        $autoReply->Body    = '
    <div style="background-color: #1f1f1f; border: 1px solid #343232; border-radius: 12px; padding: 24px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; color: #f1f1f1; max-width: 550px;">
        <h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #ffffff; border-bottom: 2px solid #49378f; padding-bottom: 10px;">
            Message Received!
        </h3>
        <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.6; color: #d1d1d1;">
            Hi <strong>' . htmlspecialchars($name) . '</strong>,
        </p>
        <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.6; color: #a1a1a1;">
            Thank you for getting in touch. I have received your message regarding <strong>"' . htmlspecialchars($subject ?: 'your inquiry') . '"</strong> and will review it as soon as possible.
        </p>
        <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #a1a1a1;">
            I usually respond within 24 to 48 hours. Have a great day!
        </p>
        <div style="border-top: 1px solid #343232; pt: 16px; margin-top: 20px;">
            <p style="margin: 16px 0 0 0; font-size: 12px; color: #717171;">
                Best regards,<br>
                <strong style="color: #ffffff;">Oluwatimilehin</strong>
            </p>
        </div>
    </div>';

        $autoReply->send();
    } catch (Exception $e) {
        // Log if the auto-reply fails, but don't stop execution since the main mail sent
        if (function_exists('logProjectError')) {
            logProjectError("Auto-reply Error: " . $autoReply->ErrorInfo);
        }
    }
}
