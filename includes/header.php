<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>GoodFix</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo isset($css_path) ? $css_path : '../css/style.css'; ?>" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo isset($home_path) ? $home_path : '../user/index.php'; ?>">
                <img src="../assets/cms-logo.png" alt="GoodFix Logo" style="width: 150px; height: 150px; margin-bottom: -50px; margin-top: -50px;" class="me-2">
            </a>
            
            <?php if (isset($show_admin_nav) && $show_admin_nav): ?>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    Dashboard
                </a>
                <a class="nav-link" href="view_complaints.php">
                    Complaints
                </a>
                <a class="nav-link" href="logout.php">
                    Logout
                </a>
            </div>
            <?php else: ?>
            <!-- Student Navigation -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="<?php echo isset($home_path) ? $home_path : '../user/index.php'; ?>">
                        Home
                    </a>
                    <a class="nav-link" href="<?php echo isset($home_path) ? str_replace('index.php', 'submit_complaint.php', $home_path) : '../user/submit_complaint.php'; ?>">
                        Submit
                    </a>
                    <a class="nav-link" href="<?php echo isset($home_path) ? str_replace('index.php', 'track_complaint.php', $home_path) : '../user/track_complaint.php'; ?>">
                        Track
                    </a>
                    <a class="nav-link" href="<?php echo isset($home_path) ? str_replace('index.php', 'profile.php', $home_path) : '../user/profile.php'; ?>">
                        Profile
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- JavaScript for navigation indicators -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show saved complaints indicator
        const savedComplaints = localStorage.getItem('goodfix_my_complaints');
        if (savedComplaints) {
            const complaints = JSON.parse(savedComplaints);
            if (complaints.length > 0) {
                const indicator = document.getElementById('savedComplaintsIndicator');
                const countElement = document.getElementById('savedCount');
                if (indicator && countElement) {
                    countElement.textContent = complaints.length;
                    indicator.style.display = 'block';
                }
            }
        }
    });
    </script>
        </div>
    </nav>
