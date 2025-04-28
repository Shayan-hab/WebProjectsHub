<?php
require_once '../includes/functions.php';

// Restrict access to logged-in Super Admins
if (!isset($_SESSION['super_admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<?php require_once '../includes/header.php'; ?>

<div class="dashboard-container">
    <h2>Welcome, Super Admin!</h2>
    <p>This is the Super Admin section of the Book Request System. Manage the system from here.</p>
    <div class="hero-image">
        <img src="../assets/images/library.jpg" alt="Super Admin Panel" style="max-width: 100%; height: auto;">
    </div>
    <div class="button-container">
        <a href="../logout.php"><button class="action-button">Logout</button></a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>