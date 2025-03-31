<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is loaded
require_once '../config/database.php'; // Ensure Dotenv is loaded

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Adjust path if needed
$dotenv->load();

function sendResetEmail($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; // Fetch from .env
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom("support@mail.com", 'Campify Support');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "Click the link below to reset your password:<br>
            <a href='http://localhost/MyProjects/Campify/authentication/reset_password.php?token=$token'>
            Reset Password</a><br>
            This link will expire in 30 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}
