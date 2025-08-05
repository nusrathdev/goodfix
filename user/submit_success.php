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
                        <?php 
                        // Generate reference number format: GF-YYYY-XXXX
                        $reference_no = 'GF-' . date('Y', strtotime($complaint['submitted_at'])) . '-' . str_pad($complaint['id'], 4, '0', STR_PAD_LEFT);
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Reference Number:</h6>
                                <div class="d-flex align-items-center">
                                    <p class="text-primary fs-4 fw-bold mb-0 me-3" id="referenceNumber"><?php echo $reference_no; ?></p>
                                    <button class="btn btn-outline-primary btn-sm" onclick="copyReference()" id="copyBtn">
                                        <i class="bi bi-copy"></i> Copy
                                    </button>
                                </div>
                                <small class="text-muted">Save this reference number to track your complaint</small>
                                
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

<!-- Reference number copy functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show important notice about reference number
    setTimeout(function() {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i> <strong>Important!</strong><br>
            Please save your reference number <strong><?php echo $reference_no; ?></strong> to track your complaint.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-hide after 8 seconds
        setTimeout(function() {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 8000);
    }, 1000);
});

// Copy reference number function
function copyReference() {
    const referenceNumber = document.getElementById('referenceNumber').textContent;
    const copyBtn = document.getElementById('copyBtn');
    
    navigator.clipboard.writeText(referenceNumber).then(function() {
        // Update button to show success
        copyBtn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        copyBtn.classList.remove('btn-outline-primary');
        copyBtn.classList.add('btn-success');
        
        // Revert button after 2 seconds
        setTimeout(function() {
            copyBtn.innerHTML = '<i class="bi bi-copy"></i> Copy';
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-outline-primary');
        }, 2000);
        
        // Show success message
        const successDiv = document.createElement('div');
        successDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        successDiv.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; max-width: 300px;';
        successDiv.innerHTML = `
            <i class="bi bi-clipboard-check"></i> Reference number copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(successDiv);
        
        setTimeout(function() {
            if (successDiv && successDiv.parentNode) {
                successDiv.remove();
            }
        }, 3000);
    }).catch(function() {
        // Fallback for browsers that don't support clipboard API
        const textArea = document.createElement('textarea');
        textArea.value = referenceNumber;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        copyBtn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        copyBtn.classList.remove('btn-outline-primary');
        copyBtn.classList.add('btn-success');
        
        setTimeout(function() {
            copyBtn.innerHTML = '<i class="bi bi-copy"></i> Copy';
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-outline-primary');
        }, 2000);
    });
}
</script>
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }, 1000);
});
</script>

<?php include '../includes/footer.php'; ?>
