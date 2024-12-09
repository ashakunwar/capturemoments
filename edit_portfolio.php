<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Fetch the portfolio item to edit
if (isset($_GET['id'])) {
    $portfolio_id = $_GET['id'];

    // Fetch the portfolio item from the database
    $stmt = $conn->prepare("SELECT * FROM portfolio_items WHERE id = ?");
    $stmt->bind_param("i", $portfolio_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $portfolio_item = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: admin.php");
    exit();
}

// Handle form submission for updating the portfolio item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $category = $_POST['category']; // Get selected category
    $image = $_FILES['image'];

    $valid_formats = ['image/jpeg', 'image/png', 'image/gif'];  // Allowed image formats

    // If a new image is uploaded, validate the image
    if (!empty($image['name'])) {
        if (!in_array($image['type'], $valid_formats)) {
            $error = "Invalid image format. Please upload JPG, PNG, or GIF files only.";
        } else {
            $image_name = basename($image['name']);
            $new_image_path = 'uploads/' . $image_name;

            // Delete the old image
            if (file_exists($portfolio_item['image_path'])) {
                unlink($portfolio_item['image_path']);
            }

            // Upload the new image
            move_uploaded_file($image['tmp_name'], $new_image_path);
            $portfolio_item['image_path'] = $new_image_path;
        }
    }

    // If there's no error, update the portfolio item in the database
    if (!isset($error)) {
        $sql = "UPDATE portfolio_items SET  category = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $category, $portfolio_item['image_path'], $portfolio_id);

        if ($stmt->execute()) {
            header("Location: admin.php?message=Portfolio item updated successfully.");
            exit();
        } else {
            $error = "Error updating portfolio item: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch categories for the dropdown
$category_query = "SELECT DISTINCT category FROM portfolio_items";
$category_result = $conn->query($category_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portfolio Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Edit Portfolio Item</h2>

        <!-- Display error messages -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form for editing the portfolio item -->
        <form action="edit_portfolio.php?id=<?php echo $portfolio_id; ?>" method="POST" enctype="multipart/form-data">
           
            <div class="mb-3">
                <label for="category" class="form-label">Select Category</label>
                <select name="category" class="form-select" id="category" required>
                    <option value="">Choose a category</option>
                    <option value="Weddings" <?php echo ($portfolio_item['category'] === 'Weddings') ? 'selected' : ''; ?>>Weddings</option>
                    <option value="Portraits" <?php echo ($portfolio_item['category'] === 'Portraits') ? 'selected' : ''; ?>>Portraits</option>
                    <option value="Landscapes" <?php echo ($portfolio_item['category'] === 'Landscapes') ? 'selected' : ''; ?>>Landscapes</option>
                    <option value="Events" <?php echo ($portfolio_item['category'] === 'Events') ? 'selected' : ''; ?>>Events</option>
                    <option value="Fashion" <?php echo ($portfolio_item['category'] === 'Fashion') ? 'selected' : ''; ?>>Fashion</option>
                    <option value="Commercial" <?php echo ($portfolio_item['category'] === 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
                    <option value="Product" <?php echo ($portfolio_item['category'] === 'Product') ? 'selected' : ''; ?>>Product</option>
                    <option value="Travel" <?php echo ($portfolio_item['category'] === 'Travel') ? 'selected' : ''; ?>>Travel</option>
                    <option value="Nature" <?php echo ($portfolio_item['category'] === 'Nature') ? 'selected' : ''; ?>>Nature</option>
                    <option value="Sports" <?php echo ($portfolio_item['category'] === 'Sports') ? 'selected' : ''; ?>>Sports</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image (JPG, PNG, GIF only)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg, image/png, image/gif">
                <p>Current Image:</p>
                <img src="<?php echo $portfolio_item['image_path']; ?>" alt="Portfolio Image" style="height: 100px;">
            </div>
            <button type="submit" class="btn btn-primary">Update Portfolio Item</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
