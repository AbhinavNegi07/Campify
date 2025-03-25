<?php
session_start();
require_once '../config/database.php';

// Create database instance
$db = new Database();
$conn = $db->conn; // Assigning the connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL Query
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name']; // Changed from 'user_name' to 'username'
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['message'] = "Invalid login credentials.";
        }
    } else {
        $_SESSION['message'] = "Invalid login credentials.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <?php
    include("../components/header.php");
    ?>

    <div class="container">
        <div class="login-container">
            <h3 class="text-center">Login</h3>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="mt-3 text-center">
                Don't have an account? <a href="register.php">Register</a>
            </p>
            <p class="text-center mt-2">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>

        </div>
    </div>

    <?php
    include("../components/footer.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-side Validation -->
    <script>
        function validateForm() {
            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let isValid = true;

            // Email Validation
            if (!email.value.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                email.classList.add("is-invalid");
                isValid = false;
            } else {
                email.classList.remove("is-invalid");
            }

            // Password Validation (Minimum 6 characters)
            if (password.value.length < 6) {
                password.classList.add("is-invalid");
                isValid = false;
            } else {
                password.classList.remove("is-invalid");
            }

            return isValid;
        }
    </script>

</body>

</html>