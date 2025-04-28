<?php
session_start();
require_once 'db_connect.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']) || isset($_SESSION['super_admin_id']);
}

function isUser() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function isSuperAdmin() {
    return isset($_SESSION['super_admin_id']);
}

function fetchBooksFromAPI($query, $pdo, $user_id) {
    // Check API request limit
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM api_requests WHERE user_id = ? AND request_time > NOW() - INTERVAL 24 HOUR");
    $stmt->execute([$user_id]);
    if ($stmt->fetchColumn() >= 5) {
        return ['error' => 'API request limit exceeded. Try again tomorrow.'];
    }

    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        return ['error' => 'Failed to fetch books from API.'];
    }

    $data = json_decode($response, true);
    $books = [];
    if (isset($data['items'])) {
        foreach ($data['items'] as $item) {
            $title = $item['volumeInfo']['title'] ?? 'Unknown';
            $author = isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Unknown';
            $books[] = ['title' => $title, 'author' => $author];
        }
    }

    // Log API request
    $stmt = $pdo->prepare("INSERT INTO api_requests (user_id) VALUES (?)");
    $stmt->execute([$user_id]);

    return $books;
}

function insertBookToDB($pdo, $title, $author, $category) {
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category) VALUES (?, ?, ?)");
    $stmt->execute([$title, $author, $category]);
    return $pdo->lastInsertId();
}
?>