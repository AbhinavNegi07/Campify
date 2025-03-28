<?php
require '../config/database.php';

$db = new Database();
$conn = $db->conn;

if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    $result = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug'");

    if (mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger text-center'>Blog not found!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger text-center'>No blog specified!</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php
    include("../components/header.php");
    ?>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-primary"><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p class="text-muted">By <strong><?php echo htmlspecialchars($blog['author']); ?></strong> | <?php echo date("F j, Y", strtotime($blog['created_at'])); ?></p>

                <div class="text-center">
                    <img src="../<?php echo htmlspecialchars($blog['image']); ?>" class="img-fluid rounded mb-3" alt="Blog Image" style="max-width: 100%; height: auto;">
                </div>

                <!-- <p class="card-text"><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p> -->
                <p class="card-text"><?php echo htmlspecialchars_decode($blog['content']); ?></p>




                <a href="blogs.php" class="btn btn-secondary mt-3">⬅ Back to Blogs</a>
            </div>
        </div>
    </div>

    <?php
    include("../components/footer.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>