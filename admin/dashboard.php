<?php
$page_title = "Admin Dashboard";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "../admin/dashboard.php";
$show_admin_nav = true;
include '../includes/header.php';
include '../includes/db.php';
include '../includes/auth.php';

// Check if admin is logged in
requireAdminLogin();

// Get statistics
$stats = [];

// Total complaints
$stmt = $pdo->query("SELECT COUNT(*) as total FROM complaints");
$stats['total'] = $stmt->fetch()['total'];

// Pending complaints
$stmt = $pdo->query("SELECT COUNT(*) as pending FROM complaints WHERE status = 'pending'");
$stats['pending'] = $stmt->fetch()['pending'];

// In progress complaints
$stmt = $pdo->query("SELECT COUNT(*) as in_progress FROM complaints WHERE status = 'in_progress'");
$stats['in_progress'] = $stmt->fetch()['in_progress'];

// Resolved complaints
$stmt = $pdo->query("SELECT COUNT(*) as resolved FROM complaints WHERE status = 'resolved'");
$stats['resolved'] = $stmt->fetch()['resolved'];

// Recent complaints
$stmt = $pdo->query("SELECT * FROM complaints WHERE status = 'pending' ORDER BY submitted_at DESC LIMIT 5");
$recent_pending_complaints = $stmt->fetchAll();

// Priority distribution
$stmt = $pdo->query("SELECT priority, COUNT(*) as count FROM complaints GROUP BY priority");
$priority_stats = $stmt->fetchAll();
?>

<main>
    <div class="container py-4">
        <!-- Welcome Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold">
                    <i class="bi bi-speedometer2 text-primary"></i> Dashboard
                </h1>
                <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
            <div class="text-end">
                <p class="mb-0 text-muted">
                    <?php echo date('F j, Y'); ?>
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h3 class="fw-bold"><?php echo $stats['total']; ?></h3>
                        <p class="text-muted mb-0">Total Complaints</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h3 class="fw-bold"><?php echo $stats['pending']; ?></h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h3 class="fw-bold"><?php echo $stats['in_progress']; ?></h3>
                        <p class="text-muted mb-0">In Progress</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h3 class="fw-bold"><?php echo $stats['resolved']; ?></h3>
                        <p class="text-muted mb-0">Resolved</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Complaints & Priority Stats -->
        <div class="row g-4">
            <!-- Recent Complaints -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Recent Pending Complaints
                        </h5>
                        <a href="view_complaints.php" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View All
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_pending_complaints)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">No pending complaints yet</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_pending_complaints as $complaint): ?>
                                    <tr>
                                        <td>
                                            <a href="complaint_detail.php?id=<?php echo $complaint['id']; ?>" class="text-decoration-none">
                                                <?php echo $complaint['id']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                        <td>
                                            <span class="text-truncate" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($complaint['subject']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status">
                                                <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="priority">
                                                <?php echo ucfirst($complaint['priority']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo date('M j, Y', strtotime($complaint['submitted_at'])); ?></small>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Priority Distribution -->
            <div class="col-lg-3 ms-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart"></i> Priority Distribution
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($priority_stats)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-pie-chart fs-1"></i>
                            <p class="mt-2">No data available</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($priority_stats as $priority): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="priority">
                                    <?php echo ucfirst($priority['priority']); ?>
                                </span>
                            </div>
                            <div>
                                <strong><?php echo $priority['count']; ?></strong>
                                <small class="text-muted">
                                    (<?php echo $stats['total'] > 0 ? round(($priority['count'] / $stats['total']) * 100) : 0; ?>%)
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning"></i> Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="view_complaints.php?filter=urgent" class="btn btn-outline-primary">
                                <i class="bi bi-exclamation-triangle"></i> Urgent Issues
                            </a>
                            <a href="view_complaints.php" class="btn btn-outline-primary">
                                <i class="bi bi-list-ul"></i> All Complaints
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
