<?php
$page_title = "My Profile";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "index.php";
include '../includes/header.php';
?>

<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="fw-bold">
                        My Profile
                    </h1>
                    <p class="lead text-muted">Manage your saved information and complaint history</p>
                </div>

                <!-- Saved Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person-lines-fill"></i> Saved Information
                            <small class="text-muted float-end">Stored in your browser</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="profileForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="student_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="student_name" placeholder="Enter your full name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" placeholder="Enter your student ID">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="faculty" class="form-label">Faculty</label>
                                    <select class="form-select" id="faculty">
                                        <option value="">Select Faculty</option>
                                        <option value="Technological Studies">Technological Studies</option>
                                        <option value="Applied Sciences">Applied Sciences</option>
                                        <option value="Management">Management</option>
                                        <option value="Medicine">Medicine</option>
                                        <option value="Animal Science & Export Agriculture">Animal Science & Export Agriculture</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-primary" onclick="saveProfile()">
                                     Save Information
                                </button>
                                <button type="button" class="btn btn-link text-primary ms-auto" onclick="clearProfile()">
                                    Clear All Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- My Complaints History -->
                <div class="card mb-4" id="complaintsHistoryCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> My Complaints History
                            <span class="badge bg-primary" id="complaintsCount">0</span>
                        </h5>
                    </div>
                    <div class="card-body" id="complaintsHistory">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card" id="statsCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up"></i> My Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h3 class="fw-bold text-primary" id="totalComplaints">0</h3>
                                    <p class="text-muted mb-0">Total Complaints</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h3 class="fw-bold text-warning" id="pendingComplaints">0</h3>
                                    <p class="text-muted mb-0">Pending</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h3 class="fw-bold text-primary" id="inProgressComplaints">0</h3>
                                    <p class="text-muted mb-0">In Progress</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box">
                                    <h3 class="fw-bold text-success" id="resolvedComplaints">0</h3>
                                    <p class="text-muted mb-0">Resolved</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Profile Management Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProfile();
    loadComplaintsHistory();
    updateStats();
});

function loadProfile() {
    const savedData = localStorage.getItem('goodfix_user_data');
    if (savedData) {
        const userData = JSON.parse(savedData);
        
        document.getElementById('student_name').value = userData.student_name || '';
        document.getElementById('student_id').value = userData.student_id || '';
        document.getElementById('email').value = userData.email || '';
        document.getElementById('faculty').value = userData.faculty || '';
    }
}

function saveProfile() {
    const userData = {
        student_name: document.getElementById('student_name').value.trim(),
        student_id: document.getElementById('student_id').value.trim(),
        email: document.getElementById('email').value.trim(),
        faculty: document.getElementById('faculty').value,
        last_updated: new Date().toISOString()
    };
    
    // Validate required fields
    if (!userData.student_name || !userData.student_id || !userData.email) {
        alert('Please fill in at least name, student ID, and email.');
        return;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(userData.email)) {
        alert('Please enter a valid email address.');
        return;
    }
    
    localStorage.setItem('goodfix_user_data', JSON.stringify(userData));
    
    // Show success message
    showAlert('Profile information saved successfully!', 'success');
}

function clearProfile() {
    if (confirm('Are you sure you want to clear all your saved information and complaint history?')) {
        localStorage.removeItem('goodfix_user_data');
        localStorage.removeItem('goodfix_my_complaints');
        
        // Clear form
        document.getElementById('profileForm').reset();
        
        // Hide cards
        document.getElementById('complaintsHistoryCard').style.display = 'none';
        document.getElementById('statsCard').style.display = 'none';
    }
}

function loadComplaintsHistory() {
    const savedComplaints = localStorage.getItem('goodfix_my_complaints');
    if (savedComplaints) {
        const complaints = JSON.parse(savedComplaints);
        if (complaints.length > 0) {
            document.getElementById('complaintsHistoryCard').style.display = 'block';
            document.getElementById('complaintsCount').textContent = complaints.length;
            displayComplaintsHistory(complaints);
        }
    }
}

function displayComplaintsHistory(complaints) {
    const container = document.getElementById('complaintsHistory');
    
    let html = '<div class="table-responsive"><table class="table table-hover">';
    html += `
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    `;
    
    complaints.forEach(function(complaint) {
        const date = new Date(complaint.submitted_at).toLocaleDateString();
        const statusColor = getStatusColor(complaint.status);
        
        html += `
            <tr>
                <td>
                    <strong>#${String(complaint.id).padStart(4, '0')}</strong>
                </td>
                <td>
                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="${complaint.subject}">
                        ${complaint.subject}
                    </span>
                </td>
                <td>
                    <span class="badge bg-${statusColor}">${complaint.status}</span>
                </td>
                <td>
                    <span class="badge priority-${complaint.priority}">${complaint.priority}</span>
                </td>
                <td>
                    <small>${date}</small>
                </td>
                <td>
                    <a href="track_complaint.php?id=${complaint.id}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Track
                    </a>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function updateStats() {
    const savedComplaints = localStorage.getItem('goodfix_my_complaints');
    if (savedComplaints) {
        const complaints = JSON.parse(savedComplaints);
        if (complaints.length > 0) {
            document.getElementById('statsCard').style.display = 'block';
            
            const stats = {
                total: complaints.length,
                pending: complaints.filter(c => c.status === 'pending').length,
                in_progress: complaints.filter(c => c.status === 'in_progress').length,
                resolved: complaints.filter(c => c.status === 'resolved').length
            };
            
            document.getElementById('totalComplaints').textContent = stats.total;
            document.getElementById('pendingComplaints').textContent = stats.pending;
            document.getElementById('inProgressComplaints').textContent = stats.in_progress;
            document.getElementById('resolvedComplaints').textContent = stats.resolved;
        }
    }
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

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 3 seconds
    setTimeout(function() {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>

<style>
.stat-box {
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
    margin-bottom: 1rem;
}
</style>

<?php include '../includes/footer.php'; ?>
