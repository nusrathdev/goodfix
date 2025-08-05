<?php
$page_title = "Complaint Details";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "../user/index.php";
$show_admin_nav = true;
include '../includes/header.php';
include '../includes/db.php';
include '../includes/auth.php';

// Check if admin is logged in
requireAdminLogin();

// Get complaint ID
$complaint_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$complaint_id) {
    header('Location: view_complaints.php');
    exit();
}

// Handle status and priority updates
if ($_POST) {
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE complaints SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$new_status, $complaint_id]);
        $success_message = "Status updated successfully!";
    }
    
    if (isset($_POST['update_priority'])) {
        $new_priority = $_POST['priority'];
        $stmt = $pdo->prepare("UPDATE complaints SET priority = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$new_priority, $complaint_id]);
        $success_message = "Priority updated successfully!";
    }
}

// Fetch complaint details
$stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
$stmt->execute([$complaint_id]);
$complaint = $stmt->fetch();

if (!$complaint) {
    header('Location: view_complaints.php');
    exit();
}
?>

<main>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <?php 
                // Generate reference number format: GF-YYYY-XXXX
                $reference_no = 'GF-' . date('Y', strtotime($complaint['submitted_at'])) . '-' . str_pad($complaint['id'], 4, '0', STR_PAD_LEFT);
                ?>
                <h1 class="fw-bold">
                    <i class="bi bi-file-earmark-text text-primary"></i> 
                    Reference: <?php echo $reference_no; ?>
                </h1>
                <p class="text-muted mb-0">
                    Submitted on <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['submitted_at'])); ?>
                    <span class="badge status-<?php echo $complaint['status']; ?> ms-2">
                        <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                    </span>
                </p>
            </div>
            <div>
                <a href="view_complaints.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Complaint Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Complaint Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Student Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted">STUDENT INFORMATION</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="fw-bold">Name:</td>
                                        <td><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Student ID:</td>
                                        <td><?php echo htmlspecialchars($complaint['student_id']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($complaint['email']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($complaint['email']); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Department:</td>
                                        <td><?php echo htmlspecialchars($complaint['department'] ?: 'Not specified'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-muted">COMPLAINT DETAILS</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="fw-bold">Reference:</td>
                                        <td>
                                            <span class="text-primary fw-bold"><?php echo $reference_no; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Type:</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($complaint['complaint_type']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Current Status:</td>
                                        <td>
                                            <span class="badge status-<?php echo $complaint['status']; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Priority:</td>
                                        <td>
                                            <span class="badge priority-<?php echo $complaint['priority']; ?>">
                                                <?php echo ucfirst($complaint['priority']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Last Updated:</td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($complaint['updated_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Complaint Content -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">SUBJECT</h6>
                            <p class="fs-5"><?php echo htmlspecialchars($complaint['subject']); ?></p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">DESCRIPTION</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
                            </div>
                        </div>

                        <!-- Progress Timeline -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">PROGRESS TIMELINE</h6>
                            <div class="timeline">
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-primary">
                                        <i class="bi bi-plus-circle text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Complaint Submitted</h6>
                                        <small class="text-muted">
                                            <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['submitted_at'])); ?>
                                        </small>
                                    </div>
                                </div>

                                <?php if ($complaint['status'] != 'pending'): ?>
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-warning">
                                        <i class="bi bi-gear text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Processing Started</h6>
                                        <small class="text-muted">Status changed to In Progress</small>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($complaint['status'] == 'resolved' || $complaint['status'] == 'closed'): ?>
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-success">
                                        <i class="bi bi-check-circle text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Issue Resolved</h6>
                                        <small class="text-muted">
                                            Last updated: <?php echo date('F j, Y \a\t g:i A', strtotime($complaint['updated_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($complaint['status'] == 'closed'): ?>
                                <div class="timeline-item active">
                                    <div class="timeline-marker bg-secondary">
                                        <i class="bi bi-archive text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Complaint Closed</h6>
                                        <small class="text-muted">Case is now closed</small>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Panel -->
            <div class="col-lg-4">
                <!-- Status Management -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-gear"></i> Manage Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" <?php echo $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="in_progress" <?php echo $complaint['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="closed" <?php echo $complaint['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-repeat"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Priority Management -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-flag"></i> Manage Priority
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Update Priority:</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low" <?php echo $complaint['priority'] == 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo $complaint['priority'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $complaint['priority'] == 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="urgent" <?php echo $complaint['priority'] == 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                </select>
                            </div>
                            <button type="submit" name="update_priority" class="btn btn-warning w-100">
                                <i class="bi bi-flag"></i> Update Priority
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Student -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-envelope"></i> Contact Student
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Send an email to the student regarding this complaint.</p>
                        <a href="mailto:<?php echo htmlspecialchars($complaint['email']); ?>?subject=Re: Complaint #<?php echo str_pad($complaint['id'], 4, '0', STR_PAD_LEFT); ?> - <?php echo urlencode($complaint['subject']); ?>&body=Dear <?php echo urlencode($complaint['student_name']); ?>,%0D%0A%0D%0ARegarding your complaint #<?php echo str_pad($complaint['id'], 4, '0', STR_PAD_LEFT); ?>:%0D%0A<?php echo urlencode($complaint['subject']); ?>%0D%0A%0D%0A" 
                           class="btn btn-outline-primary w-100">
                            <i class="bi bi-envelope"></i> Send Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Timeline CSS -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.timeline-content {
    padding-left: 20px;
}

.timeline-content h6 {
    margin-bottom: 4px;
    font-weight: 600;
}
</style>

<?php include '../includes/footer.php'; ?>
