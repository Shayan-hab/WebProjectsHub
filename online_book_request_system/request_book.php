<?php
require_once 'includes/functions.php';

if (!isUser()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$categories = ['App Development', 'Mobile Development', 'AI'];
$notification = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $file_path = null;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_path = $upload_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }

    // Insert book to database
    $book_id = insertBookToDB($pdo, $title, $author, $category);

    // Insert request
    $stmt = $pdo->prepare("INSERT INTO book_requests (user_id, book_id, category, file_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $category, $file_path]);
    $notification = "Your request for '$title' has been submitted.";
}

// Fetch books from API
$books = [];
if (isset($_GET['category'])) {
    $query = $_GET['category'] === 'App Development' ? 'app development' : ($_GET['category'] === 'Mobile Development' ? 'mobile development' : 'artificial intelligence');
    $books = fetchBooksFromAPI($query, $pdo, $user_id);
}
?>

<?php require_once 'includes/header.php'; ?>

<h2>Request a Book</h2>
<?php if ($notification): ?>
    <div class="notification"><?php echo $notification; ?></div>
<?php endif; ?>
<?php if (isset($books['error'])): ?>
    <div class="error"><?php echo $books['error']; ?></div>
<?php endif; ?>
<form method="GET">
    <label for="category">Select Category</label>
    <select id="category" name="category" onchange="this.form.submit()">
        <option value="">--Select--</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php echo isset($_GET['category']) && $_GET['category'] === $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
        <?php endforeach; ?>
    </select>
</form>

<?php if (!empty($books) && !isset($books['error'])): ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Book Title</label>
        <select id="title" name="title" required>
            <?php foreach ($books as $book): ?>
                <option value="<?php echo htmlspecialchars($book['title']); ?>" data-author="<?php echo htmlspecialchars($book['author']); ?>"><?php echo htmlspecialchars($book['title']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="author">Author</label>
        <input type="text" id="author" name="author" readonly required>
        
        <label for="category">Category</label>
        <input type="text" name="category" value="<?php echo isset($_GET['category']) ? $_GET['category'] : ''; ?>" readonly>
        
        <label for="file">Upload File (Optional)</label>
        <input type="file" id="file" name="file">
        
        <button type="submit">Submit Request</button>
    </form>
<?php endif; ?>

<script>
document.getElementById('title').addEventListener('change', function() {
    let author = this.options[this.selectedIndex].getAttribute('data-author');
    document.getElementById('author').value = author;
});
</script>

<?php require_once 'includes/footer.php'; ?>