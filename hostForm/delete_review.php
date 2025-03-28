<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

// Ensure required fields are present
if (!isset($_POST['review_id'], $_POST['slug'])) {
    die("Missing required fields.");
}

$review_id = $_POST['review_id'];
$user_id = $_SESSION['user_id'] ?? null;
$slug = $_POST['slug']; // Get slug for redirection

if (!$user_id) {
    die("Error: User not logged in.");
}

// Database connection
$db = new Database();
$conn = $db->conn;

// Verify that the logged-in user is the owner of the review
$stmt = $conn->prepare("SELECT user_id FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review || $review['user_id'] !== $user_id) {
    die("Error: You are not authorized to delete this review.");
}

// Delete the review
$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);

if ($stmt->execute()) {
    $_SESSION['messages'][] = "Review deleted successfully!";
} else {
    $_SESSION['messages'][] = "Error deleting review.";
}

// Redirect back to the campground details page
header("Location: campground_details.php?slug=" . urlencode($slug));
exit;
