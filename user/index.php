<?php
$page_title = "Home";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="bi bi-tools"></i> Welcome to GoodFix
                    </h1>
                    <p class="lead mb-4">
                        Your university complaint management system. Submit issues, track progress, and help make our campus better.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="submit_complaint.php" class="btn btn-light btn-lg">
                            <i class="bi bi-plus-circle"></i> Submit Complaint
                        </a>
                        <a href="track_complaint.php" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-search"></i> Track Complaint
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-chat-square-dots display-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="fw-bold">How GoodFix Works</h2>
                    <p class="lead text-muted">Simple steps to resolve your university issues</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-pencil-square fs-4"></i>
                            </div>
                            <h5 class="card-title">1. Submit Complaint</h5>
                            <p class="card-text">Fill out a simple form with details about your issue or concern.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-gear fs-4"></i>
                            </div>
                            <h5 class="card-title">2. We Process</h5>
                            <p class="card-text">Our team reviews and assigns your complaint to the right department.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <h5 class="card-title">3. Get Resolution</h5>
                            <p class="card-text">Track progress and receive updates until your issue is resolved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h3 class="fw-bold mb-4">Need Help Right Now?</h3>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="submit_complaint.php" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-plus-circle me-2"></i>
                                Submit New Complaint
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="track_complaint.php" class="btn btn-outline-primary btn-lg w-100">
                                <i class="bi bi-search me-2"></i>
                                Track Your Complaint
                            </a>
                        </div>
                    </div>
                    <p class="text-muted mt-3">
                        <small>Having trouble? Contact support at support@university.edu</small>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
$js_path = "../js/script.js";
include '../includes/footer.php'; 
?>
