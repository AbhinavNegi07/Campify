<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];
$db = new Database();
$conn = $db->conn;

// Verify token and expiry time
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token.");
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();

        $_SESSION['message'] = "Password updated successfully. You can now log in.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Font Awesome for Icons -->
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Reset Password</h3>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                                            unset($_SESSION['error']); ?></div>
                        <?php endif; ?>

                        <form method="POST" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'togglePasswordIcon1')">
                                        <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', 'togglePasswordIcon2')">
                                        <i class="fas fa-eye" id="togglePasswordIcon2"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }

        function togglePassword(fieldId, iconId) {
            let field = document.getElementById(fieldId);
            let icon = document.getElementById(iconId);

            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

</body>

</html>