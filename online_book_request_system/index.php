<?php
require_once 'includes/functions.php';
?>

<?php require_once 'includes/header.php'; ?>

<div class="home-container">
    <h2>Welcome to the Book Request System</h2>
    <p>Explore, request, and manage books seamlessly.</p>
    
    <div class="hero-image">
        <img src="assets/images/library.jpg" alt="Library" style="max-width: 100%; height: auto;">
    </div>
    
    <div class="button-container">
        <a href="register.php"><button class="action-button">Register</button></a>
        <a href="login.php"><button class="action-button">Login</button></a>
        <a href="admin/login.php"><button class="action-button">Admin</button></a>
        <a href="super_admin/login.php"><button class="action-button">Super Admin</button></a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>