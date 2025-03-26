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
    $errors = [];

    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = preg_replace("/[^0-9+]/", "", $_POST['phone']);
    $description = htmlspecialchars($_POST['description']);

    if (!$email) {
        $errors[] = "Invalid email format.";
    }

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

    if (!empty($errors)) {
        $_SESSION['messages'] = $errors;
        header("Location: campground_form.php");
        exit;
    }

    // ✅ Register campground and generate slug
    $campground_id = $campground->register($name, $location, $email, $phone, $description, "", $user_id);

    if ($campground_id === false) {
        $_SESSION['messages'] = ["⚠️ Registration failed. Please try again."];
        header("Location: campground_form.php");
        exit;
    }

    // ✅ Fetch the slug for directory creation
    $slug = $campground->getSlugById($campground_id);
    $upload_dir = "../uploads/" . $slug;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // ✅ Handle multiple image uploads
    if (!empty($_FILES['images']['name'][0])) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB
        $image_paths = [];

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$key]);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $target_file = $upload_dir . "/" . uniqid() . "_" . $file_name;

            if (in_array($file_ext, $allowed_types) && $_FILES['images']['size'][$key] <= $max_file_size) {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $image_paths[] = $target_file;
                }
            }
        }

        // ✅ Insert image paths into campground_images table
        $stmt = $db->conn->prepare("INSERT INTO campground_images (campground_id, image_path) VALUES (?, ?)");
        foreach ($image_paths as $path) {
            $stmt->bind_param("is", $campground_id, $path);
            $stmt->execute();
        }
    }

    $_SESSION['messages'] = ["🎉 Campground registered successfully!"];
    header("Location: campground_details.php?slug=" . $slug);
    exit;
}
