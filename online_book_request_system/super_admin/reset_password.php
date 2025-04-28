<?php
require_once '../includes/functions.php';

if (!isSuperAdmin()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_password = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);
    header('Location: reset_password.php?success=Password reset.');
    exit;
}

$stmt = $pdo->query("SELECT id, username FROM users");
$users = $stmt->fetchAll();
?>

<?php require_once '../includes/header.php'; ?>

<h2>Reset User Password</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="notification"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<form method="POST">
    <label for="user_id">Select User</label>
    <select id="user_id" name="user_id" required>
        <option value="">--Select User--</option>
        <?php foreach ($users as $user): ?>
            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
        <?php endforeach; ?>
    </select>
    
    <label for="new_password">New Password</label>
    <input type="password" id="new_password" name="new_password" required>
    
    <button type="submit">Reset Password</button>
</form>

<?php require_once '../includes/footer.php'; ?>