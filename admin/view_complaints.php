<?php
$page_title = "View Complaints";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "../admin/dashboard.php";
$show_admin_nav = true;
include '../includes/header.php';
include '../includes/db.php';
include '../includes/auth.php';

// Check if admin is logged in
requireAdminLogin();

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $complaint_id = (int)$_POST['complaint_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE complaints SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $complaint_id]);
    
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Get filter parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build query
$where_conditions = [];
$params = [];

if ($filter) {
    if ($filter == 'urgent') {
        $where_conditions[] = "priority = 'urgent'";
    } else {
        $where_conditions[] = "status = ?";
        $params[] = $filter;
    }
}

if ($search) {
    $where_conditions[] = "(student_name LIKE ? OR student_id LIKE ? OR subject LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_query = "SELECT COUNT(*) as total FROM complaints $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_complaints = $stmt->fetch()['total'];
$total_pages = ceil($total_complaints / $per_page);

// Get complaints
$query = "SELECT * FROM complaints $where_clause ORDER BY submitted_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$complaints = $stmt->fetchAll();
?>

<main>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold">
                    </i> All Complaints
                </h1>
                <p class="text-muted mb-0">Manage and track student complaints</p>
            </div>
            <div>
                <span class="text-black"><?php echo $total_complaints; ?> Total</span>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="filter" class="form-label">Filter by Status:</label>
                        <select class="form-select" id="filter" name="filter">
                            <option value="">All Complaints</option>
                            <option value="pending" <?php echo $filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="in_progress" <?php echo $filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="resolved" <?php echo $filter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="closed" <?php echo $filter == 'closed' ? 'selected' : ''; ?>>Closed</option>
                            <option value="urgent" <?php echo $filter == 'urgent' ? 'selected' : ''; ?>>Urgent Priority</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search:</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Search by name, ID, subject, or description..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <a href="view_complaints.php" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Complaints Table -->
        <?php if (empty($complaints)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-text fs-1 text-muted"></i>
                <h4 class="mt-3">No Complaints Found</h4>
                <p class="text-muted">
                    <?php if ($filter || $search): ?>
                    Try adjusting your search criteria or filters.
                    <?php else: ?>
                    No complaints have been submitted yet.
                    <?php endif; ?>
                </p>
                <a href="view_complaints.php" class="btn btn-primary">
                    <i class="bi bi-eye"></i> View All Complaints
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td>
                                <a href="complaint_detail.php?id=<?php echo $complaint['id']; ?>" class="text-decoration-none fw-bold">
                                    <?php echo $complaint['id']; ?>
                                </a>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($complaint['student_name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($complaint['student_id']); ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($complaint['subject']); ?>">
                                    <?php echo htmlspecialchars($complaint['subject']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="type">
                                    <?php echo htmlspecialchars($complaint['complaint_type']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="priority">
                                    <?php echo ucfirst($complaint['priority']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                    <select name="status" class="form-select form-select-sm status-<?php echo $complaint['status']; ?>" 
                                            onchange="this.form.submit()" style="width: auto;">
                                        <option value="pending" <?php echo $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="in_progress" <?php echo $complaint['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                        <option value="closed" <?php echo $complaint['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('M j, Y', strtotime($complaint['submitted_at'])); ?><br>
                                    <span class="text-muted"><?php echo date('g:i A', strtotime($complaint['submitted_at'])); ?></span>
                                </small>
                            </td>
                            <td>
                                <a href="complaint_detail.php?id=<?php echo $complaint['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Complaints pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Previous button -->
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter=<?php echo urlencode($filter); ?>&search=<?php echo urlencode($search); ?>">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </li>

                <!-- Page numbers -->
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&filter=<?php echo urlencode($filter); ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>

                <!-- Next button -->
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter=<?php echo urlencode($filter); ?>&search=<?php echo urlencode($search); ?>">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
