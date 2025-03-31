<?php
require 'includes/auth.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->conn;

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    $_SESSION['messages'] = ["⚠️ Invalid request!"];
    header("Location: manage_campgrounds.php");
    exit;
}

$action = $_GET['action'];
$campground_id = intval($_GET['id']);

// Fetch campground details to get slug and images
$campgroundQuery = "SELECT slug, email, name FROM campgrounds WHERE id = ?";
$stmt = $conn->prepare($campgroundQuery);
$stmt->bind_param("i", $campground_id);
$stmt->execute();
$campground = $stmt->get_result()->fetch_assoc();

if (!$campground) {
    $_SESSION['messages'] = ["⚠️ Campground not found!"];
    header("Location: manage_campgrounds.php");
    exit;
}

$slug = $campground['slug'];
$folderPath = "../uploads/" . $slug;

if ($action === 'approve') {
    $sql = "UPDATE campgrounds SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $campground_id);
    $stmt->execute();

    $_SESSION['messages'] = ["✅ Campground approved successfully!"];
} elseif ($action === 'reject' || $action === 'delete') {
    // Delete images from storage
    $imageQuery = $conn->prepare("SELECT image_path FROM campground_images WHERE campground_id = ?");
    $imageQuery->bind_param("i", $campground_id);
    $imageQuery->execute();
    $imageResult = $imageQuery->get_result();

    while ($imageRow = $imageResult->fetch_assoc()) {
        $imagePath = $folderPath . "/" . basename($imageRow['image_path']);
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete image file
        }
    }

    // Remove images from the database
    $deleteImagesQuery = $conn->prepare("DELETE FROM campground_images WHERE campground_id = ?");
    $deleteImagesQuery->bind_param("i", $campground_id);
    $deleteImagesQuery->execute();

    // Delete the campground record
    $deleteQuery = $conn->prepare("DELETE FROM campgrounds WHERE id = ?");
    $deleteQuery->bind_param("i", $campground_id);
    $deleteQuery->execute();

    // Delete folder if it's empty
    if (is_dir($folderPath)) {
        rmdir($folderPath);
    }

    $_SESSION['messages'] = ["❌ Campground " . ($action === 'reject' ? "rejected" : "deleted") . " and images removed!"];
} else {
    $_SESSION['messages'] = ["⚠️ Invalid action!"];
}

header("Location: manage_campgrounds.php");
exit;
