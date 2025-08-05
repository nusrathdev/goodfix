<?php
$page_title = "Submit Complaint";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
include '../includes/db.php';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_POST) {
    $student_name = trim($_POST['student_name']);
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);
    $department = $_POST['department'];
    $complaint_type = $_POST['complaint_type'];
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];

    // Basic validation
    if (empty($student_name) || empty($student_id) || empty($email) || empty($subject) || empty($description)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        try {
            // Insert complaint into database
            $stmt = $pdo->prepare("INSERT INTO complaints (student_name, student_id, email, department, complaint_type, subject, description, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_name, $student_id, $email, $department, $complaint_type, $subject, $description, $priority]);
            
            $complaint_id = $pdo->lastInsertId();
            header("Location: submit_success.php?id=" . $complaint_id);
            exit();
        } catch (PDOException $e) {
            $error_message = "Error submitting complaint. Please try again.";
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
                        <i class="bi bi-plus-circle text-primary"></i> Submit Complaint
                    </h1>
                    <p class="lead text-muted">Tell us about your issue and we'll work to resolve it</p>
                </div>

                <!-- Alert Messages -->
                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Complaint Form -->
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="complaintForm">
                            <div class="row">
                                <!-- Student Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="student_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="student_name" name="student_name" 
                                           value="<?php echo isset($_POST['student_name']) ? htmlspecialchars($_POST['student_name']) : ''; ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Student ID *</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" 
                                           value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select" id="department" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Computer Science" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                        <option value="Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                                        <option value="Business" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Business') ? 'selected' : ''; ?>>Business</option>
                                        <option value="Arts" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Arts') ? 'selected' : ''; ?>>Arts</option>
                                        <option value="Science" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Science') ? 'selected' : ''; ?>>Science</option>
                                        <option value="Other" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="complaint_type" class="form-label">Complaint Type</label>
                                    <select class="form-select" id="complaint_type" name="complaint_type">
                                        <option value="Academic" <?php echo (isset($_POST['complaint_type']) && $_POST['complaint_type'] == 'Academic') ? 'selected' : ''; ?>>Academic</option>
                                        <option value="Facility" <?php echo (isset($_POST['complaint_type']) && $_POST['complaint_type'] == 'Facility') ? 'selected' : ''; ?>>Facility</option>
                                        <option value="Technology" <?php echo (isset($_POST['complaint_type']) && $_POST['complaint_type'] == 'Technology') ? 'selected' : ''; ?>>Technology</option>
                                        <option value="Administrative" <?php echo (isset($_POST['complaint_type']) && $_POST['complaint_type'] == 'Administrative') ? 'selected' : ''; ?>>Administrative</option>
                                        <option value="Other" <?php echo (isset($_POST['complaint_type']) && $_POST['complaint_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="priority" class="form-label">Priority Level</label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="low" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                                        <option value="medium" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'medium') ? 'selected' : ''; ?> selected>Medium</option>
                                        <option value="high" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
                                        <option value="urgent" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 'urgent') ? 'selected' : ''; ?>>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Complaint Details -->
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       placeholder="Brief description of the issue"
                                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Please provide detailed information about your complaint..."
                                          required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Submit Complaint
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Home
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
