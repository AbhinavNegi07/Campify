<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format!";
        header("Location: forgot_password.php");
        exit;
    }

    // Database connection
    $db = new Database();
    $conn = $db->conn;

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generate a secure token
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send Reset Email
        require_once '../utils/send_reset_email.php';
        if (sendResetEmail($email, $token)) {
            $_SESSION['message'] = "A password reset link has been sent to your email!";
        } else {
            $_SESSION['message'] = "Error sending email. Please try again later.";
        }
    } else {
        $_SESSION['message'] = "No account found with this email.";
    }

    header("Location: forgot_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
</head>

<body>
    <h2>Forgot Password</h2>

    <?php if (isset($_SESSION['message'])) : ?>
        <p><?php echo htmlspecialchars($_SESSION['message']);
            unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email Address:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>

</html>