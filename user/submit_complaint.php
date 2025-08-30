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
    $faculty = $_POST['faculty'];
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
            $stmt = $pdo->prepare("INSERT INTO complaints (student_name, student_id, email, faculty, complaint_type, subject, description, priority) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_name, $student_id, $email, $faculty, $complaint_type, $subject, $description, $priority]);
            
            $complaint_id = $pdo->lastInsertId();
            
            // Set cookie to save complaint ID for tracking
            setcookie('last_complaint_id', $complaint_id, time() + (30 * 24 * 60 * 60), '/'); // 30 days
            
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
                        Submit Complaint
                    </h1>
                    <p class="lead text-muted">Tell us about your issue and we'll work to resolve it</p>
                </div>

                <!-- Alert Messages -->
                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
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
                                    <label for="faculty" class="form-label">Faculty</label>
                                    <select class="form-select" id="faculty" name="faculty">
                                        <option value="">Select Faculty</option>
                                        <option value="Technological Studies" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Technological Studies') ? 'selected' : ''; ?>>Technological Studies</option>
                                        <option value="Applied Sciences" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Applied Sciences') ? 'selected' : ''; ?>>Applied Sciences</option>
                                        <option value="Management" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Management') ? 'selected' : ''; ?>>Management</option>
                                        <option value="Medicine" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Medicine') ? 'selected' : ''; ?>>Medicine</option>
                                        <option value="Animal Science & Export Agriculture" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Animal Science & Export Agriculture') ? 'selected' : ''; ?>>Animal Science & Export Agriculture</option>
                                        <option value="Other" <?php echo (isset($_POST['faculty']) && $_POST['faculty'] == 'Other') ? 'selected' : ''; ?>>Other</option>
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
                                    Submit Complaint
                                </button>
                                <a href="index.php" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Auto-fill Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('complaintForm');
    
    // Auto-fill form with saved data
    function loadSavedData() {
        const savedData = localStorage.getItem('goodfix_user_data');
        if (savedData) {
            const userData = JSON.parse(savedData);
            
            // Fill form fields
            if (userData.student_name) document.getElementById('student_name').value = userData.student_name;
            if (userData.student_id) document.getElementById('student_id').value = userData.student_id;
            if (userData.email) document.getElementById('email').value = userData.email;
            if (userData.faculty) document.getElementById('faculty').value = userData.faculty;
        }
    }
    
    // Save form data to localStorage
    function saveUserData() {
        const userData = {
            student_name: document.getElementById('student_name').value,
            student_id: document.getElementById('student_id').value,
            email: document.getElementById('email').value,
            faculty: document.getElementById('faculty').value,
            last_updated: new Date().toISOString()
        };
        
        localStorage.setItem('goodfix_user_data', JSON.stringify(userData));
    }
    
    // Save data when form fields change
    ['student_name', 'student_id', 'email', 'faculty'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        field.addEventListener('blur', saveUserData);
    });
    
    // Save complaint ID to list when form is submitted
    form.addEventListener('submit', function() {
        // Save user data
        saveUserData();
        
        // This will run after successful submission
        setTimeout(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const complaintId = urlParams.get('id');
            if (complaintId) {
                let myComplaints = JSON.parse(localStorage.getItem('goodfix_my_complaints') || '[]');
                
                const complaintData = {
                    id: complaintId,
                    subject: document.getElementById('subject').value,
                    submitted_at: new Date().toISOString(),
                    status: 'pending'
                };
                
                myComplaints.unshift(complaintData);
                
                // Keep only last 20 complaints
                if (myComplaints.length > 20) {
                    myComplaints = myComplaints.slice(0, 20);
                }
                
                localStorage.setItem('goodfix_my_complaints', JSON.stringify(myComplaints));
            }
        }, 100);
    });
    
    // Load saved data on page load
    loadSavedData();
    
    // Add clear data button
    const clearBtn = document.createElement('button');
    clearBtn.type = 'button';
    clearBtn.className = 'btn btn-link btn-sm text-primary ms-auto';
    clearBtn.innerHTML = 'Clear Saved Data';
    clearBtn.onclick = function() {
        if (confirm('Are you sure you want to clear your saved information?')) {
            localStorage.removeItem('goodfix_user_data');
            form.reset();
        }
    };
    
    // Add clear button next to form actions
    const formActions = document.querySelector('.d-flex.gap-3');
    formActions.appendChild(clearBtn);
});
</script>

<?php include '../includes/footer.php'; ?>
