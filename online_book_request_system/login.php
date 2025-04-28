<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: user_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: user_dashboard.php');
        exit;
    }
    
    // Check admin
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: admin/dashboard.php');
        exit;
    }
    
    // Check super admin
    $stmt = $pdo->prepare("SELECT * FROM super_admins WHERE username = ?");
    $stmt->execute([$username]);
    $super_admin = $stmt->fetch();
    
    if ($super_admin && password_verify($password, $super_admin['password'])) {
        $_SESSION['super_admin_id'] = $super_admin['id'];
        header('Location: super_admin/index.php');
        exit;
    }
    
    $error = "Invalid username or password.";
}
?>

<?php require_once 'includes/header.php'; ?>

<h2>Login</h2>
<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
    <div class="notification"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<form method="POST">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a>.</p>

<?php require_once 'includes/footer.php'; ?>