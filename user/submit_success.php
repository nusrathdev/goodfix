<?php
$page_title = "Complaint Submitted";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
include '../includes/db.php';

// Get complaint ID from URL
$complaint_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch complaint details
$complaint = null;
if ($complaint_id) {
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
    $stmt->execute([$complaint_id]);
    $complaint = $stmt->fetch();
}

if (!$complaint) {
    header('Location: index.php');
    exit();
}
?>

<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="text-center mb-5">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-check-lg fs-1"></i>
                    </div>
                    <h1 class="fw-bold text-success">Complaint Submitted Successfully!</h1>
                    <p class="lead text-muted">Your complaint has been received and will be reviewed by our team.</p>
                </div>

                <!-- Complaint Details Card -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text"></i> Complaint Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Complaint ID:</h6>
                                <p class="text-primary fs-4 fw-bold">#<?php echo str_pad($complaint['id'], 4, '0', STR_PAD_LEFT); ?></p>
                                
                                <h6 class="fw-bold">Student Name:</h6>
                                <p><?php echo htmlspecialchars($complaint['student_name']); ?></p>
                                
                                <h6 class="fw-bold">Department:</h6>
                                <p><?php echo htmlspecialchars($complaint['department'] ?: 'Not specified'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Status:</h6>
                                <span class="badge bg-warning text-dark">Pending</span>
                                
                                <h6 class="fw-bold mt-3">Priority:</h6>
                                <span class="badge priority-<?php echo $complaint['priority']; ?>">
                                    <?php echo ucfirst($complaint['priority']); ?>
                                </span>
                                
                                <h6 class="fw-bold mt-3">Submitted:</h6>
                                <p><?php echo date('F j, Y \a\t g:i A', strtotime($complaint['submitted_at'])); ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="fw-bold">Subject:</h6>
                        <p><?php echo htmlspecialchars($complaint['subject']); ?></p>
                        
                        <h6 class="fw-bold">Description:</h6>
                        <p><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="alert alert-info mt-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-info-circle"></i> Important Information
                    </h5>
                    <ul class="mb-0">
                        <li><strong>Save your Complaint ID:</strong> #<?php echo str_pad($complaint['id'], 4, '0', STR_PAD_LEFT); ?> for future reference</li>
                        <li><strong>Track your complaint:</strong> Use the tracking page to check status updates</li>
                        <li><strong>Email updates:</strong> You'll receive notifications at <?php echo htmlspecialchars($complaint['email']); ?></li>
                        <li><strong>Response time:</strong> Most complaints are reviewed within 24-48 hours</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="track_complaint.php?id=<?php echo $complaint['id']; ?>" class="btn btn-primary">
                            <i class="bi bi-search"></i> Track This Complaint
                        </a>
                        <a href="submit_complaint.php" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Submit Another
                        </a>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
