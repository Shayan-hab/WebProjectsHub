<?php
require_once '../includes/functions.php';

if (!isSuperAdmin()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    header('Location: manage_admins.php?success=Admin added.');
    exit;
}

if (isset($_GET['delete'])) {
    $admin_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    header('Location: manage_admins.php?success=Admin deleted.');
    exit;
}

$stmt = $pdo->query("SELECT id, username FROM admins");
$admins = $stmt->fetchAll();
?>

<?php require_once '../includes/header.php'; ?>

<h2>Manage Admins</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="notification"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<form method="POST">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Add Admin</button>
</form>
<table>
    <tr>
        <th>Username</th>
        <th>Action</th>
    </tr>
    <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?php echo htmlspecialchars($admin['username']); ?></td>
            <td><a href="?delete=<?php echo $admin['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>