<?php
require 'includes/auth.php';
require_once '../config/database.php';
// require '../includes/mailer.php'; // PHPMailer script

$db = new Database();
$conn = $db->conn;

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    $_SESSION['messages'] = ["⚠️ Invalid request!"];
    header("Location: manage_campgrounds.php");
    exit;
}

$action = $_GET['action'];
$campground_id = intval($_GET['id']);

// Fetch campground details
$campgroundQuery = "SELECT * FROM campgrounds WHERE id = ?";
$stmt = $conn->prepare($campgroundQuery);
$stmt->bind_param("i", $campground_id);
$stmt->execute();
$campground = $stmt->get_result()->fetch_assoc();
$email = $campground['email'];
$name = $campground['name'];

if ($action == 'approve') {
    $sql = "UPDATE campgrounds SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $campground_id);
    $stmt->execute();

    // sendEmail($email, "Campground Approved", "Your campground '$name' has been approved and is now live on our platform.");
    $_SESSION['messages'] = ["✅ Campground approved successfully!"];
} elseif ($action == 'reject') {
    $sql = "DELETE FROM campgrounds WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $campground_id);
    $stmt->execute();

    // sendEmail($email, "Campground Rejected", "Your campground '$name' has been rejected. Please contact support for details.");
    $_SESSION['messages'] = ["❌ Campground rejected and deleted!"];
} elseif ($action == 'delete') {
    $deleteImagesQuery = "DELETE FROM campground_images WHERE campground_id = ?";
    $stmtImages = $conn->prepare($deleteImagesQuery);
    $stmtImages->bind_param("i", $campground_id);
    $stmtImages->execute();

    $deleteQuery = "DELETE FROM campgrounds WHERE id = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    $stmtDelete->bind_param("i", $campground_id);
    $stmtDelete->execute();

    $_SESSION['messages'] = ["🗑️ Campground deleted successfully!"];
} else {
    $_SESSION['messages'] = ["⚠️ Invalid action!"];
}

header("Location: manage_campgrounds.php");
exit;
