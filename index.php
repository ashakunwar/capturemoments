<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captured Moments - Portfolio</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .hero {
            position: relative;
            height: 90vh; 
            background: url('e9a692e6a5b51ed0d8faabdbe598d3f8.jpg') no-repeat center center/cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-style: italic;
            text-align: center;
            padding: 0 10%; 
        }
        .hero h1 {
            
            margin-bottom: 10px; 
        }
        .card-img-top {
    width: 100%; /* Ensure the image fills the card width */
    height: 200px; /* Fixed height for consistent landscape dimensions */
    object-fit: cover; /* Crop the image to fill the defined dimensions */
    display: block; /* Prevent spacing issues */
    margin: 0 auto; /* Center the image horizontally if needed */
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
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero bg-dark text-light text-center py-5">
        <div class="container">
            <h1 class="display-4">Welcome to Captured Moments</h1>
            <p class="lead">Showcasing the finest moments, beautifully captured.</p>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Portfolio</h2>
            <div class="row">
                <!-- Example Portfolio Item 1 -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="IMG_6377.JPG" class="card-img-top" alt="Wedding Photography">
                        <div class="card-body">
                            <h5 class="card-title">Wedding Photography</h5>
                            <p class="card-text">A collection of beautiful wedding moments captured with precision and emotion.</p>
                        </div>
                    </div>
                </div>
                <!-- Example Portfolio Item 2 -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="FD9EB27E-FFFB-48D8-B5E4-52B01FD14C12.JPG" class="card-img-top" alt="Nature Photography">
                        <div class="card-body">
                            <h5 class="card-title">Nature Photography</h5>
                            <p class="card-text">Capturing the essence of nature's beauty, from landscapes to wildlife.</p>
                        </div>
                    </div>
                </div>
                <!-- Example Portfolio Item 3 -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="audience-1853662_640.jpg" class="card-img-top" alt="Event Photography">
                        <div class="card-body">
                            <h5 class="card-title">Event Photography</h5>
                            <p class="card-text">Professional photography for all kinds of events, capturing the best moments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Captured Moments Photography. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
