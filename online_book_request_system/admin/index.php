<?php
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}
?>

<?php require_once '../includes/header.php'; ?>

<h2>Welcome, Admin!</h2>
<p>This is the Admin section of the Book Request System. Use the dashboard to view user requests and system statistics.</p>
<div class="hero-image">
    <img src="../assets/images/library.jpg" alt="Admin Panel" style="max-width: 100%; height: auto;">
</div>
<div class="button-container">
    <a href="dashboard.php"><button class="action-button">Go to Dashboard</button></a>
    <a href="../logout.php"><button class="action-button">Logout</button></a>
</div>

<?php require_once '../includes/footer.php'; ?>