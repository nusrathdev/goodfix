<?php
$page_title = "Track Complaint";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
include '../includes/db.php';

$complaint = null;
$error_message = '';

// Check if complaint ID is provided
if (isset($_GET['id'])) {
    $complaint_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
    $stmt->execute([$complaint_id]);
    $complaint = $stmt->fetch();
    
    if (!$complaint) {
        $error_message = "Complaint not found. Please check your complaint ID.";
    }
}

// Handle search form
if ($_POST && isset($_POST['search_type'])) {
    $search_type = $_POST['search_type'];
    $search_value = trim($_POST['search_value']);
    
    if (empty($search_value)) {
        $error_message = "Please enter a search value.";
    } else {
        if ($search_type == 'id') {
            $stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
            $stmt->execute([(int)$search_value]);
        } elseif ($search_type == 'email') {
            $stmt = $pdo->prepare("SELECT * FROM complaints WHERE email = ? ORDER BY submitted_at DESC LIMIT 1");
            $stmt->execute([$search_value]);
        } elseif ($search_type == 'student_id') {
            $stmt = $pdo->prepare("SELECT * FROM complaints WHERE student_id = ? ORDER BY submitted_at DESC LIMIT 1");
            $stmt->execute([$search_value]);
        }
        
        $complaint = $stmt->fetch();
        
        if (!$complaint) {
            $error_message = "No complaint found with the provided information.";
        }
    }
}
?>

<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="fw-bold">
                        <i class="bi bi-search text-primary"></i> Track Your Complaint
                    </h1>
                    <p class="lead text-muted">Enter your complaint details to check status and updates</p>
                </div>

                <!-- Search Form -->
                <?php if (!$complaint): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Find Your Complaint</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="search_type" class="form-label">Search By:</label>
                                <select class="form-select" id="search_type" name="search_type" required>
                                    <option value="id" <?php echo (isset($_POST['search_type']) && $_POST['search_type'] == 'id') ? 'selected' : ''; ?>>Complaint ID</option>
                                    <option value="email" <?php echo (isset($_POST['search_type']) && $_POST['search_type'] == 'email') ? 'selected' : ''; ?>>Email Address</option>
                                    <option value="student_id" <?php echo (isset($_POST['search_type']) && $_POST['search_type'] == 'student_id') ? 'selected' : ''; ?>>Student ID</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="search_value" class="form-label">Enter Value:</label>
                                <input type="text" class="form-control" id="search_value" name="search_value" 
                                       placeholder="Enter your complaint ID, email, or student ID"
                                       value="<?php echo isset($_POST['search_value']) ? htmlspecialchars($_POST['search_value']) : ''; ?>" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search Complaint
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Complaint Details -->
                <?php if ($complaint): ?>
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text"></i> Complaint #<?php echo str_pad($complaint['id'], 4, '0', STR_PAD_LEFT); ?>
                        </h5>
                        <span class="badge status-<?php echo $complaint['status']; ?>">
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
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($complaint['department'] ?: 'Not specified'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Complaint Details:</h6>
                                <p><strong>Type:</strong> <?php echo htmlspecialchars($complaint['complaint_type']); ?></p>
                                <p><strong>Priority:</strong> 
                                    <span class="badge priority-<?php echo $complaint['priority']; ?>">
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
                        
                        <!-- Status Progress -->
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
                                        <i class="bi bi-archive"></i>
                                    </div>
                                    <small>Closed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center mt-4">
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="track_complaint.php" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i> Track Another
                        </a>
                        <a href="submit_complaint.php" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Submit New Complaint
                        </a>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Additional CSS for progress steps -->
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

<?php include '../includes/footer.php'; ?>
