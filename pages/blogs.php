<?php
require '../config/database.php';

$db = new Database();
$conn = $db->conn;

// Fetch all blogs
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php
    include("../components/header.php");
    ?>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">📖 Latest Blogs</h2>

        <div class="row">
            <?php while ($blog = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="../<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a>
                            </h5>
                            <p class="text-muted">By <strong><?php echo htmlspecialchars($blog['author']); ?></strong> | <?php echo date("F j, Y", strtotime($blog['created_at'])); ?></p>
                            <!-- <p class="card-text"><?php echo htmlspecialchars(substr($blog['content'], 0, 100)); ?>...</p> -->
                            <p class="card-text">
                                <?php echo substr(strip_tags(html_entity_decode($blog['content'], ENT_QUOTES | ENT_HTML5)), 0, 100) . '...'; ?>
                            </p>







                            <a href="blog.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" class="btn btn-primary">📖 Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php
    include("../components/footer.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>