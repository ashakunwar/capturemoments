<?php
// Include the database connection
include 'db.php';

// Fetch unique categories for filtering
$category_query = "SELECT DISTINCT category FROM portfolio_items";
$category_result = $conn->query($category_query);

// Handle filtering
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Pagination setup
$items_per_page = 6; // Number of items to display per page
$total_items_query = "SELECT COUNT(*) FROM portfolio_items" . ($selected_category ? " WHERE category = '$selected_category'" : "");
$total_items_result = $conn->query($total_items_query);
$total_items = $total_items_result->fetch_row()[0];
$total_pages = ceil($total_items / $items_per_page);

// Get the current page from the query string
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($total_pages, $current_page)); // Ensure page number is valid

// Calculate the offset for the query
$offset = ($current_page - 1) * $items_per_page;

// Fetch portfolio items from the database with LIMIT and filtering
$portfolio_sql = "SELECT * FROM portfolio_items" . ($selected_category ? " WHERE category = '$selected_category'" : "") . " ORDER BY created_at DESC LIMIT $offset, $items_per_page";
$portfolio_result = $conn->query($portfolio_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captured Moments - Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <style>
        /* Existing styles */
        .gallery-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 0;
            margin-bottom: 20px;
        }

        .gallery-card img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s;
            border-radius: 0;
        }



        .gallery-card:hover img {
            transform: scale(1.1); /* Zoom in on hover */
        }

        
    </style>
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
                    <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Gallery Section -->
    <section class="gallery py-5">
        <div class="container">
            <h2 class="text-center mb-4">Photo Gallery</h2>
            
            <!-- Filter Form -->
            <form class="mb-4" method="GET" action="gallery.php">
                <div class="form-group">
                    <label for="category">Filter by Category:</label>
                    <select name="category" class="form-select" id="category">
                        <option value="">All Categories</option>
                        <?php while ($category_row = $category_result->fetch_assoc()): ?>
                            <option value="<?php echo $category_row['category']; ?>" <?php echo ($category_row['category'] === $selected_category) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category_row['category']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Filter</button>
            </form>

            <div class="row">
                <?php if ($portfolio_result->num_rows > 0): ?>
                    <?php while($row = $portfolio_result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card gallery-card">
                                <a href="<?php echo $row['image_path']; ?>" data-lightbox="gallery" data-title="<?php echo htmlspecialchars($row['title']); ?>">
                                    <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No portfolio items found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i === $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="gallery.php?page=<?php echo $i; ?>&category=<?php echo urlencode($selected_category); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Captured Moments Photography. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS and Lightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
