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
                        Welcome to GoodFix
                    </h1>
                    <p class="lead mb-4">
                        Your university complaint management system. Submit issues, track progress, and help make our campus better.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="submit_complaint.php" class="btn btn-light btn-lg">
                            </i>Submit Complaint
                        </a>
                        <a href="track_complaint.php" class="btn btn-outline-light btn-lg border-white">
                            Track Complaint
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <h2 class="text-white-50">University Complaint System</h2>
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
                                <strong>1</strong>
                            </div>
                            <h5 class="card-title">Submit Complaint</h5>
                            <p class="card-text">Fill out a simple form with details about your issue. You'll receive a secure reference number for tracking.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <strong>2</strong>
                            </div>
                            <h5 class="card-title">We Process</h5>
                            <p class="card-text">Our team reviews and assigns your complaint to the right department for prompt resolution.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <strong>3</strong>
                            </div>
                            <h5 class="card-title">Get Resolution</h5>
                            <p class="card-text">Track progress with your secure reference number and receive updates until your issue is fully resolved.</p>
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
                <div class="col-md-10 text-center">
                    <h3 class="fw-bold mb-4">Ready to Get Started?</h3>
                    
                    <!-- Quick Track Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-2">
                                        <i class="bi bi-search text-primary me-2"></i>Track Existing Complaint
                                    </h5>
                                    <p class="text-muted mb-3">Already have a reference number? Enter it below to check your complaint status instantly.</p>
                                    <form method="POST" action="track_complaint.php" class="d-flex gap-2">
                                        <input type="text" name="reference_no" class="form-control" 
                                               placeholder="Enter reference (e.g., GFX-202508-1234-A7B9)" 
                                               pattern="^GFX-\d{6}-\d+-[A-Z0-9]{4}$"
                                               required>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Track
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-primary">
                                        <i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="submit_complaint.php" class="btn btn-primary btn-lg w-100">
                                Submit New Complaint
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="track_complaint.php" class="btn btn-outline-primary btn-lg w-100">
                                Track with Reference
                            </a>
                        </div>
                    </div>
                    
                    <!-- Important Information -->
                    <div class="mt-4 p-3 bg-white rounded border-start border-4 border-primary">
                        <div class="row align-items-center">
                            <div class="col-md-8 text-start">
                                <h6 class="text-primary mb-1">
                                    <i class="bi bi-info-circle me-2"></i>Important Information
                                </h6>
                                <p class="mb-0 small text-muted">
                                    Keep your reference number safe - it's the only way to track your complaint. 
                                    Reference numbers are secure and unique to prevent unauthorized access.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <small class="text-muted">
                                    Need help? <a href="mailto:support@university.edu" class="text-decoration-none">Contact Support</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Home Page Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize page interactions
    console.log('GoodFix System - Secure Complaint Management');
    
    // Track button focus enhancement
    const trackForm = document.querySelector('#quickTrackingForm');
    if (trackForm) {
        const refInput = trackForm.querySelector('input[name="reference"]');
        if (refInput) {
            refInput.addEventListener('focus', function() {
                this.placeholder = 'Example: GFX-202501-123-AB7D';
            });
            
            refInput.addEventListener('blur', function() {
                this.placeholder = 'Enter your complaint reference number...';
            });
        }
    }
});
</script>

<?php 
$js_path = "../js/script.js";
include '../includes/footer.php'; 
?>
