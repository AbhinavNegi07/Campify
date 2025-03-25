<?php
session_start();
require_once '../config/database.php';
require_once 'campground.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['messages'] = ["⚠️ You must be logged in to create a campground."];
    header("Location: ../authentication/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$db = new Database();
$campground = new Campground($db->conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = []; // Array to store validation errors

    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = preg_replace("/[^0-9+]/", "", $_POST['phone']);
    $description = htmlspecialchars($_POST['description']);

    // 🛑 Validate Email
    if (!$email) {
        $errors[] = "Invalid email format.";
    }

    // 🛑 Check if Campground Name, Email, or Phone already exists
    if ($campground->campgroundExists($name, $email, $phone)) {
        if ($campground->isNameTaken($name)) {
            $errors[] = "Campground name already exists. Please choose a different name.";
        }
        if ($campground->isEmailTaken($email)) {
            $errors[] = "This email is already registered. Use a different email.";
        }
        if ($campground->isPhoneTaken($phone)) {
            $errors[] = "Phone number is already in use.";
        }
    }

    // Handle Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . uniqid() . "_" . $img_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB limit

        $mime_type = mime_content_type($_FILES['image']['tmp_name']);
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($imageFileType, $allowed_types) && in_array($mime_type, $allowed_mime_types) && $_FILES['image']['size'] <= $max_file_size) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Invalid image format or size too large. Allowed formats: JPG, PNG, GIF (Max 2MB).";
        }
    }

    // 🛑 If errors exist, store them as an array and redirect back
    if (!empty($errors)) {
        $_SESSION['messages'] = $errors;  // Store errors as an array
        header("Location: campground_form.php");
        exit;
    }

    // ✅ Register the campground if there are no errors
    $campground_id = $campground->register($name, $location, $email, $phone, $description, $image, $user_id);


    if ($campground_id !== false) {
        $_SESSION['messages'] = ["🎉 Campground registered successfully!"];
        header("Location: campground_details.php?id=" . $campground_id);
        exit;
    } else {
        $_SESSION['messages'] = ["⚠️ Registration failed. Please try again."];
        header("Location: campground_form.php");
        exit;
    }
}
