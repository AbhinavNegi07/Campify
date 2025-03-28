<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

// Debugging: Print received form data
// echo "<pre>";
// print_r($_POST);
// print_r($_SESSION);
// echo "</pre>";
// exit;

// Ensure required fields are present
if (!isset($_POST['campground_id'], $_POST['rating'], $_POST['review'], $_POST['slug'])) {
    die("Missing required fields.");
}

$campground_id = $_POST['campground_id'];
$rating = intval($_POST['rating']);
$review = trim($_POST['review']);
$user_id = $_SESSION['user_id'] ?? null;
$slug = $_POST['slug']; // Get slug from form for redirection

if (!$user_id) {
    die("Error: User not logged in.");
}

// Database connection
$db = new Database();
$conn = $db->conn;
if (!$conn) {
    die("Database connection failed.");
}

// Insert review into database
$stmt = $conn->prepare("INSERT INTO reviews (campground_id, user_id, rating, review, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $campground_id, $user_id, $rating, $review);

if ($stmt->execute()) {
    $_SESSION['messages'][] = "Review submitted successfully!";
} else {
    $_SESSION['messages'][] = "Error submitting review.";
}

// Redirect back to the campground details page
header("Location: campground_details.php?slug=" . urlencode($slug));
exit;
