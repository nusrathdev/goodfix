<?php
$page_title = "Track Complaint";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
include '../includes/db.php';

$complaint = null;
$error_message = '';

// Check if complaint ID is available
if (isset($_GET['id'])) {
    $complaint_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
    $stmt->execute([$complaint_id]);
    $complaint = $stmt->fetch();
    
    if (!$complaint) {
        $error_message = "Complaint not found. Please check your complaint ID.";
    }
}

// search form
if ($_POST && isset($_POST['reference_no'])) {
    $reference_no = trim($_POST['reference_no']);
    
    if (empty($reference_no)) {
        $error_message = "Please enter a reference number.";
    } else {
        // Extract reference number
        // Reference format: GFX-YYYYMM-XXXX-HASH
        if (preg_match('/^GFX-\d{6}-(\d+)-[A-Z0-9]{4}$/', $reference_no, $matches)) {
            $complaint_id = (int)$matches[1];
            $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
            $stmt->execute([$complaint_id]);
            $temp_complaint = $stmt->fetch();
            
            if ($temp_complaint) {
                $expected_hash = strtoupper(substr(md5($temp_complaint['id'] . $temp_complaint['submitted_at'] . 'GoodFix2025'), 0, 4));
                $provided_hash = substr($reference_no, -4);
                
                if ($expected_hash === $provided_hash) {
                    $complaint = $temp_complaint;
                } else {
                    $error_message = "Invalid reference number: " . htmlspecialchars($reference_no);
                }
            } else {
                $error_message = "No complaint found with reference number: " . htmlspecialchars($reference_no);
            }
        } else {
            $error_message = "Invalid reference number format. Please use format: GFX-YYYYMM-XXXX-HASH (e.g., GFX-202508-1234-A7B9)";
        }
    }
}
?>

<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Head -->
                <div class="text-center mb-5">
                    <h1 class="fw-bold">
                      Track Your Complaint
                    </h1>
                    <p class="lead text-muted">Enter your complaint reference number to check status and updates</p>
                </div>

                <!-- search form -->
                <?php if (!$complaint): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Find Your Complaint
                        </h5>
                        <p class="mb-0 text-muted small mt-1">Use the reference number provided when you submitted your complaint</p>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="reference_no" class="form-label fw-semibold">Complaint Reference Number</label>
                                <input type="text" class="form-control form-control-lg" id="reference_no" name="reference_no" 
                                       placeholder="e.g., GFX-202508-1234-A7B9"
                                       pattern="^GFX-\d{6}-\d+-[A-Z0-9]{4}$"
                                       title="Reference format: GFX-YYYYMM-XXXX-HASH (e.g., GFX-202508-1234-A7B9)"
                                       value="<?php echo isset($_POST['reference_no']) ? htmlspecialchars($_POST['reference_no']) : ''; ?>" 
                                       required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle text-primary"></i> 
                                    Reference number format: <strong>GFX-YYYYMM-XXXX-HASH</strong> (Example: GFX-202508-1234-A7B9)
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search me-2"></i>Track Complaint
                            </button>
                        </form>
                        
                        <!-- help section -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="text-primary mb-2">
                                <i class="bi bi-question-circle me-1"></i>Can't find your reference number?
                            </h6>
                            <ul class="small text-muted mb-0">
                                <li>Check your email for the confirmation message</li>
                                <li>Look for the reference number in your submission receipt</li>
                                <li>Contact support at <a href="mailto:complaint@uwu.ac.lk">support@uwu.ac.lk</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- error message -->
                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- complaint details -->
                <?php if ($complaint): ?>
                <?php 
                $year_month = date('Ym', strtotime($complaint['submitted_at']));
                $complaint_id_padded = str_pad($complaint['id'], 4, '0', STR_PAD_LEFT);
                $hash = strtoupper(substr(md5($complaint['id'] . $complaint['submitted_at'] . 'GoodFix2025'), 0, 4));
                $reference_no = 'GFX-' . $year_month . '-' . $complaint_id_padded . '-' . $hash;
                ?>
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Reference: <?php echo $reference_no; ?>
                        </h5>
                        <span class="status text-white">
                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Student Information:</h6>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($complaint['student_name']); ?></p>
                                <p><strong>ID:</strong> <?php echo htmlspecialchars($complaint['student_id']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($complaint['email']); ?></p>
                                <p><strong>Faculty:</strong> <?php echo htmlspecialchars($complaint['faculty'] ?: 'Not specified'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Complaint Details:</h6>
                                <p><strong>Reference:</strong> <span class="text-primary fw-bold"><?php echo $reference_no; ?></span></p>
                                <p><strong>Type:</strong> <?php echo htmlspecialchars($complaint['complaint_type']); ?></p>
                                <p><strong>Priority:</strong> 
                                    <span class="priority">
                                        <?php echo ucfirst($complaint['priority']); ?>
                                    </span>
                                </p>
                                <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['submitted_at'])); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['updated_at'])); ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="fw-bold">Subject:</h6>
                        <p><?php echo htmlspecialchars($complaint['subject']); ?></p>
                        
                        <h6 class="fw-bold">Description:</h6>
                        <p><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                        <hr>
                        <h6 class="fw-bold">Progress Tracking:</h6>
                        <div class="progress-steps">
                            <div class="d-flex justify-content-between">
                                <div class="text-center">
                                    <div class="step-circle <?php echo ($complaint['status'] == 'pending' || $complaint['status'] == 'in_progress' || $complaint['status'] == 'resolved' || $complaint['status'] == 'closed') ? 'active' : ''; ?>">
                                        <i class="bi bi-plus-circle"></i>
                                    </div>
                                    <small>Submitted</small>
                                </div>
                                <div class="text-center">
                                    <div class="step-circle <?php echo ($complaint['status'] == 'in_progress' || $complaint['status'] == 'resolved' || $complaint['status'] == 'closed') ? 'active' : ''; ?>">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <small>In Progress</small>
                                </div>
                                <div class="text-center">
                                    <div class="step-circle <?php echo ($complaint['status'] == 'resolved' || $complaint['status'] == 'closed') ? 'active' : ''; ?>">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <small>Resolved</small>
                                </div>
                                <div class="text-center">
                                    <div class="step-circle <?php echo ($complaint['status'] == 'closed') ? 'active' : ''; ?>">
                                        <i class="bi bi-x-circle"></i>
                                    </div>
                                    <small>Closed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUttons-->
                <div class="text-center mt-4">
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="track_complaint.php" class="btn btn-outline-primary">
                            Track Another
                        </a>
                        <a href="submit_complaint.php" class="btn btn-outline-primary">
                            Submit New Complaint
                        </a>
                        <a href="index.php" class="btn btn-outline-primary ms-auto">
                            <i class="bi bi-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Additional CSS -->
<style>
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    transition: all 0.3s ease;
}

.step-circle.active {
    background-color: #0d6efd;
    color: white;
}

.progress-steps {
    position: relative;
    margin: 20px 0;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 2px;
    background-color: #e9ecef;
    z-index: -1;
}
</style>

<script>
// form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const referenceInput = document.getElementById('reference_no');
    
    if (form && referenceInput) {
        // Realtime validation
        referenceInput.addEventListener('input', function() {
            const value = this.value.toUpperCase();
            this.value = value;
            
            const pattern = /^GFX-\d{6}-\d+-[A-Z0-9]{4}$/;
            const isValid = pattern.test(value) || value === '';
            
            this.classList.toggle('is-invalid', value !== '' && !isValid);
            this.classList.toggle('is-valid', isValid && value !== '');
        });
        
        // Format helper
        referenceInput.addEventListener('blur', function() {
            let value = this.value.toUpperCase().replace(/[^GFX0-9A-Z-]/g, '');
            this.value = value;
        });
        
        // Form submission
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Searching...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Copy reference number
    const referenceElements = document.querySelectorAll('.text-primary.fw-bold');
    referenceElements.forEach(function(element) {
        if (element.textContent.startsWith('GFX-')) {
            element.style.cursor = 'pointer';
            element.title = 'Click to copy reference number';
            
            element.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(function() {
                    // Show copied notification
                    const originalText = element.textContent;
                    element.textContent = 'Copied!';
                    element.classList.add('text-primary');
                    
                    // remove copied notification
                    setTimeout(function() {
                        element.textContent = originalText;
                    }, 1500);
                });
            });
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
