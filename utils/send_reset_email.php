<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendResetEmail($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        // Change to your SMTP server
        $mail->Host = $_ENV['SMTP_HOST']; // Change to your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER']; // Your SMTP email
        $mail->Password = $_ENV['SMTP_PASS']; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('your-email@gmail.com', 'Campify Support');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "Click the link below to reset your password:<br>
                       <a href='http://localhost/MyProjects/Campify/authentication/reset_password.php?token=$token'>Reset Password</a><br>
                       This link will expire in 30 minutes.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
    }
}
