<?php
require_once '../config/database.php';
require_once 'campground.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to delete a campground.";
    header("Location: login.php");
    exit;
}

$db = new Database();
$campground = new Campground($db->conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure campground ID is provided
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        $_SESSION['message'] = "Invalid campground ID.";
        header("Location: view_campgrounds.php");
        exit;
    }

    $campground_id = $_POST['id'];
    $user_id = $_SESSION['user_id']; // Logged-in user ID

    // Attempt to delete
    $delete_status = $campground->deleteCampground($campground_id, $user_id);

    if ($delete_status === true) {
        $_SESSION['message'] = "Campground deleted successfully!";
    } else {
        $_SESSION['message'] = "Error: You are not authorized to delete this campground.";
    }

    header("Location: view_campgrounds.php");
    exit;
}
