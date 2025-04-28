<?php
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM super_admins WHERE username = ?");
    $stmt->execute([$username]);
    $super_admin = $stmt->fetch();
    
    if ($super_admin && password_verify($password, $super_admin['password'])) {
        $_SESSION['super_admin_id'] = $super_admin['id'];
        header('Location: index.php');
        exit;
    }
    
    $error = "Invalid credentials.";
}
?>

<?php require_once '../includes/header.php'; ?>

<div class="form-container">
    <h2>Super Admin Login</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit" class="action-button">Login</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>