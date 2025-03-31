<?php
require 'includes/auth.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->conn;

// Fetch pending campgrounds
$sql = "SELECT * FROM campgrounds WHERE status = 'pending' ORDER BY created_at DESC";
$result = $conn->query($sql);

// Fetch approved campgrounds
$approved_sql = "SELECT * FROM campgrounds WHERE status = 'approved' ORDER BY created_at DESC";
$approved_result = $conn->query($approved_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campgrounds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-primary text-center">Manage Campgrounds</h2>

        <!-- Pending Campgrounds -->
        <h4 class="mt-4">Pending Approvals</h4>
        <table class="table table-bordered">
            <thead class="table-warning">
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td>
                            <a href="campground_actions.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm" onclick="showConfirmation(event, this.href, 'approve')">
                                <i class="bi bi-check-circle"></i> Approve
                            </a>
                            <a href="campground_actions.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="showConfirmation(event, this.href, 'reject')">
                                <i class="bi bi-x-circle"></i> Reject
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Approved Campgrounds -->
        <h4 class="mt-4">Approved Campgrounds</h4>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Price</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $approved_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td>
                            <a href="campground_actions.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-dark btn-sm" onclick="showConfirmation(event, this.href, 'delete')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap Modal for Confirmation -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmBtn" class="btn btn-primary">Confirm</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showConfirmation(event, url, actionType) {
            event.preventDefault();

            let message = "";
            if (actionType === 'approve') {
                message = "Are you sure you want to <b>approve</b> this campground?";
            } else if (actionType === 'reject') {
                message = "Are you sure you want to <b>reject</b> and <b>delete</b> this campground?";
            } else if (actionType === 'delete') {
                message = "Are you sure you want to <b>delete</b> this campground permanently?";
            }

            document.getElementById("confirmMessage").innerHTML = message;
            document.getElementById("confirmBtn").href = url;

            let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }
    </script>

</body>

</html>