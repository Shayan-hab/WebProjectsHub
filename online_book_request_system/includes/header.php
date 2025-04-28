<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request System</title>
    <link rel="stylesheet" href="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/') !== false || strpos($_SERVER['REQUEST_URI'], 'super_admin/') !== false) ? '../assets/css/style.css' : 'assets/css/style.css'; ?>">
</head>
<body>
    <header>
        <?php if (isset($_SESSION['admin_id'])): ?>
            <h1>Admin Dashboard</h1>
        <?php elseif (isset($_SESSION['super_admin_id'])): ?>
            <h1>Super Admin Panel</h1>
        <?php else: ?>
            <h1>Book Request System</h1>
        <?php endif; ?>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../index.php">Home</a>
                <a href="../user_dashboard.php">Dashboard</a>
                <a href="../request_book.php">Request Book</a>
                <a href="../logout.php">Logout</a>
            <?php elseif (isset($_SESSION['admin_id'])): ?>
                <a href="../index.php">Home</a>
                <a href="dashboard.php">Admin Dashboard</a>
                <a href="../logout.php">Logout</a>
            <?php elseif (isset($_SESSION['super_admin_id'])): ?>
                <a href="../index.php">Home</a>
                <a href="index.php">Super Admin Panel</a>
                <a href="../logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
                <a href="admin/login.php">Admin</a>
                <a href="super_admin/login.php">Super Admin</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>