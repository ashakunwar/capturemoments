<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the session timeout duration (15 minutes)
$timeout_duration = 900; // 900 seconds = 15 minutes

// Check if the user is logged in and if session has expired
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
} elseif (isset($_SESSION['logged_in_time']) && (time() - $_SESSION['logged_in_time']) > $timeout_duration) {
    // If session has expired, destroy session and redirect to login page
    session_unset();
    session_destroy();
    header("Location: login.php?message=Session expired. Please log in again.");
    exit();
} else {
    // Update the logged-in time to the current time
    $_SESSION['logged_in_time'] = time();
}

// Include the database connection
include 'db.php';

// Fetch portfolio items
$portfolio_sql = "SELECT * FROM portfolio_items ORDER BY created_at DESC";
$portfolio_result = $conn->query($portfolio_sql);

// Fetch contact messages
$messages_sql = "SELECT * FROM contact_messages ORDER BY submitted_at DESC";
$messages_result = $conn->query($messages_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Captured Moments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Captured Moments</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Content -->
    <section class="admin py-5">
        <div class="container">

            <!-- Manage Portfolio Section -->
            <div class="accordion mb-4" id="portfolioAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPortfolio">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePortfolio" aria-expanded="true" aria-controls="collapsePortfolio">
                            Manage Portfolio Items
                        </button>
                    </h2>
                    <div id="collapsePortfolio" class="accordion-collapse collapse show" aria-labelledby="headingPortfolio" data-bs-parent="#portfolioAccordion">
                        <div class="accordion-body">
                            <a href="add_portfolio.php" class="btn btn-primary mb-3">Add New Portfolio Item</a>

                            <!-- Portfolio Table -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th> <!-- New column for Category -->
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($portfolio_result->num_rows > 0): ?>
                                        <?php while($row = $portfolio_result->fetch_assoc()): ?>
                                            <tr>
                                                
                                                <td><?php echo htmlspecialchars($row['category']); ?></td> <!-- Display category -->
                                                <td><img src="<?php echo $row['image_path']; ?>" alt="Portfolio Image" style="height: 100px;"></td>
                                                <td>
                                                    <a href="edit_portfolio.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_portfolio.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this portfolio item?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No portfolio items found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Contact Messages Section -->
            <div class="accordion mb-4" id="messagesAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMessages">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMessages" aria-expanded="false" aria-controls="collapseMessages">
                            View Contact Messages
                        </button>
                    </h2>
                    <div id="collapseMessages" class="accordion-collapse collapse" aria-labelledby="headingMessages" data-bs-parent="#messagesAccordion">
                        <div class="accordion-body">
                            <!-- Messages Table -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Message</th>
                                        <th>Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($messages_result->num_rows > 0): ?>
                                        <?php while($message = $messages_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $message['id']; ?></td>
                                                <td><?php echo htmlspecialchars($message['name']); ?></td>
                                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                                <td><?php echo htmlspecialchars($message['message']); ?></td>
                                                <td><?php echo $message['submitted_at']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No messages found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout Button (inside the page) -->
            <div class="text-end mb-3">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>

        </div>
    </section>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Captured Moments Photography. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
