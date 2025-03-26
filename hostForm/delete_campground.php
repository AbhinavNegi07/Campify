<?php
require_once '../config/database.php';
require_once 'campground.php';

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to delete a campground.";
    header("Location: login.php");
    exit;
}

$db = new Database();
$campground = new Campground($db->conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['slug']) || empty($_POST['slug'])) {
        $_SESSION['message'] = "Invalid campground.";
        header("Location: view_campgrounds.php");
        exit;
    }

    $campground_slug = $_POST['slug'];
    $user_id = $_SESSION['user_id']; // Logged-in user ID

    // Fetch campground details
    $camp = $campground->getCampgroundBySlug($campground_slug);

    if (!$camp) {
        $_SESSION['message'] = "Campground not found.";
        header("Location: view_campgrounds.php");
        exit;
    }

    // Ensure the logged-in user is the owner
    if ($camp['user_id'] != $user_id) {
        $_SESSION['message'] = "Error: You are not authorized to delete this campground.";
        header("Location: view_campgrounds.php");
        exit;
    }

    // Retrieve all image file paths before deleting from the database
    $slug_folder = __DIR__ . "/../uploads/$campground_slug/";

    if (is_dir($slug_folder)) {
        // Get all files inside the folder
        $files = glob("$slug_folder/*"); // Get all files in the folder

        // Delete each file
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Delete file
            }
        }

        // After deleting images, remove the folder itself
        if (rmdir($slug_folder)) {
            $_SESSION['message'] = "Campground and its images deleted successfully!";
        } else {
            $_SESSION['message'] = "Error: Failed to delete campground folder.";
        }
    }

    // Delete the campground from the database
    $delete_status = $campground->deleteCampground($campground_slug, $user_id);

    if (!$delete_status) {
        $_SESSION['message'] = "Error: Failed to delete campground.";
    }

    header("Location: view_campgrounds.php");
    exit;
}
