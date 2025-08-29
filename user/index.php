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
                            Submit Complaint
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
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <strong>1</strong>
                            </div>
                            <h5 class="card-title">Submit Complaint</h5>
                            <p class="card-text">Fill out a simple form with details about your issue or concern.</p>
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
                            <p class="card-text">Our team reviews and assigns your complaint to the right department.</p>
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
                    
                    <!-- My Complaints Quick Access (Hidden by default) -->
                    <div id="myComplaintsQuick" class="card mb-4" style="display: none;">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-bookmark-heart"></i> Your Recent Complaints
                            </h6>
                        </div>
                        <div class="card-body" id="quickComplaintsList">
                            <!-- Populated by JavaScript -->
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

<!-- Home Page Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved complaints and show quick access
    const savedComplaints = localStorage.getItem('goodfix_my_complaints');
    if (savedComplaints) {
        const complaints = JSON.parse(savedComplaints);
        if (complaints.length > 0) {
            showQuickComplaints(complaints.slice(0, 3)); // Show only first 3
        }
    }
    
    function showQuickComplaints(complaints) {
        const quickCard = document.getElementById('myComplaintsQuick');
        const quickList = document.getElementById('quickComplaintsList');
        
        let html = '<div class="row g-2">';
        complaints.forEach(function(complaint) {
            const statusColor = getStatusColor(complaint.status);
            html += `
                <div class="col-md-4">
                    <div class="card card-body text-center h-100">
                        <h6 class="mb-1">
                            <a href="track_complaint.php?id=${complaint.id}" class="text-decoration-none">
                                #${String(complaint.id).padStart(4, '0')}
                            </a>
                        </h6>
                        <p class="small text-truncate mb-2" title="${complaint.subject}">
                            ${complaint.subject}
                        </p>
                        <span class="badge bg-${statusColor}">${complaint.status}</span>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        if (complaints.length > 0) {
            html += `
                <div class="text-center mt-3">
                    <a href="track_complaint.php" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye"></i> View All My Complaints
                    </a>
                </div>
            `;
        }
        
        quickList.innerHTML = html;
        quickCard.style.display = 'block';
    }
    
    function getStatusColor(status) {
        switch(status) {
            case 'pending': return 'warning';
            case 'in_progress': return 'primary';
            case 'resolved': return 'success';
            case 'closed': return 'secondary';
            default: return 'secondary';
        }
    }
});
</script>

<?php 
$js_path = "../js/script.js";
include '../includes/footer.php'; 
?>
