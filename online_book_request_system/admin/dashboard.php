<?php
require_once '../includes/functions.php';

// Restrict access to logged-in Admins
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// Fetch statistics
$stmt = $pdo->query("SELECT COUNT(DISTINCT username) AS total_users FROM users");
$total_users = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) AS total_requests FROM book_requests");
$total_requests = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) AS in_progress FROM book_requests WHERE status = 'Pending'");
$in_progress = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) AS completed FROM book_requests WHERE status = 'Completed'");
$completed = $stmt->fetchColumn();

// Fetch book requests
$stmt = $pdo->query("SELECT u.username, br.book_id, br.status 
                     FROM book_requests br 
                     JOIN users u ON br.user_id = u.id 
                     ORDER BY br.created_at DESC");
$requests = $stmt->fetchAll();
?>

<?php require_once '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="hero-image">
        <img src="../assets/images/library.jpg" alt="Admin Dashboard" style="max-width: 100%; height: auto;">
    </div>
    
    <h3>System Overview</h3>
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                    <th>Username</th>
                    <th>Book ID</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Stats Rows -->
                <tr>
                    <td>Total Users</td>
                    <td><?php echo htmlspecialchars($total_users); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Total Requests</td>
                    <td><?php echo htmlspecialchars($total_requests); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>In Progress</td>
                    <td><?php echo htmlspecialchars($in_progress); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Completed</td>
                    <td><?php echo htmlspecialchars($completed); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <!-- Book Requests -->
                <?php foreach ($requests as $index => $request): ?>
                    <tr>
                        <td>Request <?php echo $index + 1; ?></td>
                        <td>-</td>
                        <td><?php echo htmlspecialchars($request['username']); ?></td>
                        <td><?php echo htmlspecialchars($request['book_id']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="button-container">
        <a href="../logout.php"><button class="action-button">Logout</button></a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>