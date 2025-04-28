<?php
require_once 'includes/functions.php';

if (!isUser() || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$request_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("UPDATE book_requests SET status = 'Cancelled' WHERE id = ? AND user_id = ? AND status = 'Pending'");
$stmt->execute([$request_id, $user_id]);

header('Location: user_dashboard.php');
exit;
?>