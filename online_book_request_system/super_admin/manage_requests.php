<?php
require_once '../includes/functions.php';

if (!isSuperAdmin()) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE book_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $request_id]);
    header('Location: manage_requests.php?success=Request updated.');
    exit;
}

if (isset($_GET['delete'])) {
    $request_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM book_requests WHERE id = ?");
    $stmt->execute([$request_id]);
    header('Location: manage_requests.php?success=Request deleted.');
    exit;
}

$stmt = $pdo->query("
    SELECT br.id, u.username, b.title, br.category, br.status
    FROM book_requests br
    JOIN users u ON br.user_id = u.id
    JOIN books b ON br.book_id = b.id
    ORDER BY br.created_at DESC
");
$requests = $stmt->fetchAll();
?>

<?php require_once '../includes/header.php'; ?>

<h2>Manage Requests</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="notification"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>
<table>
    <tr>
        <th>Username</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($requests as $request): ?>
        <tr>
            <td><?php echo htmlspecialchars($request['username']); ?></td>
            <td><?php echo htmlspecialchars($request['title']); ?></td>
            <td><?php echo $request['category']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="Pending" <?php echo $request['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $request['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $request['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?php echo $request['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    <input type="hidden" name="update_status" value="1">
                </form>
            </td>
            <td><a href="?delete=<?php echo $request['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once '../includes/footer.php'; ?>