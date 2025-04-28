<?php
require_once '../includes/functions.php';

if (!isSuperAdmin()) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header('Location: manage_users.php?success=User deleted.');
    exit;
}

$stmt = $pdo->query("SELECT id, username, email FROM users");
$users = $stmt->fetchAll();
?>

<?php require_once '../includes/header.php'; ?>

<h2>Manage Users</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="notification"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<table>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>