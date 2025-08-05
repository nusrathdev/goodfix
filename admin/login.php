<?php
$page_title = "Admin Login";
$css_path = "../css/style.css";
$js_path = "../js/script.js";
$home_path = "../user/index.php";
include '../includes/header.php';
include '../includes/db.php';
include '../includes/auth.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                loginAdmin($admin['id'], $admin['username']);
                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error_message = "Login error. Please try again.";
        }
    }
}
?>

<main>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card login-card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <h2 class="fw-bold">
                                    <i class="bi bi-shield-lock text-primary"></i><br>
                                    Admin Login
                                </h2>
                                <p class="text-muted">GoodFix Management Portal</p>
                            </div>

                            <!-- Error Message -->
                            <?php if ($error_message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <!-- Login Form -->
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                               required autofocus>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </form>

                            <!-- Demo Credentials -->
                            <div class="alert alert-info">
                                <small>
                                    <strong>Demo Credentials:</strong><br>
                                    Username: <code>admin</code><br>
                                    Password: <code>password</code>
                                </small>
                            </div>

                            <!-- Back to Home -->
                            <div class="text-center">
                                <a href="../user/index.php" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> Back to GoodFix
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
