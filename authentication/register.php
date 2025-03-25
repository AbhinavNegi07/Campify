<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$conn = $db->conn; // Assigning the connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['message'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $_SESSION['message'] = "Password must be at least 6 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password])) {
            $_SESSION['message'] = "Registration successful. Please log in.";
            header("Location: login.php");
            exit;
        } else {
            $_SESSION['message'] = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f4f4;
        }

        .register-container {
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
        <div class="register-container">
            <h3 class="text-center">Register</h3>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message'];
                    unset($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Name is required.</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="mt-3 text-center">
                Already have an account? <a href="login.php">Login</a>
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
            let name = document.getElementById("name");
            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let isValid = true;

            // Name Validation
            if (name.value.trim() === "") {
                name.classList.add("is-invalid");
                isValid = false;
            } else {
                name.classList.remove("is-invalid");
            }

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