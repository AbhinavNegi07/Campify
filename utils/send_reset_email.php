<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is loaded
require '../config/database.php'; // Ensure Dotenv is loaded

function sendResetEmail($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST'); // Fetch from .env
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Debugging (remove after testing)
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        // Email settings
        $mail->setFrom(getenv('SMTP_USER'), 'Campify Support');
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
