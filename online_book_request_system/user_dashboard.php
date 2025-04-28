<?php
require_once 'includes/functions.php';

if (!isUser()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user requests
$stmt = $pdo->prepare("
    SELECT br.id, b.title, br.category, br.status, br.created_at
    FROM book_requests br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = ?
    ORDER BY br.created_at DESC
");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>

<?php require_once 'includes/header.php'; ?>

<h2>User Dashboard</h2>
<h3>Your Book Requests</h3>
<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php foreach ($requests as $request): ?>
        <tr>
            <td><?php echo htmlspecialchars($request['title']); ?></td>
            <td><?php echo $request['category']; ?></td>
            <td><?php echo $request['status']; ?></td>
            <td><?php echo $request['created_at']; ?></td>
            <td>
                <?php if ($request['status'] === 'Pending'): ?>
                    <button onclick="confirmCancel(<?php echo $request['id']; ?>)">Cancel</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once 'includes/footer.php'; ?>