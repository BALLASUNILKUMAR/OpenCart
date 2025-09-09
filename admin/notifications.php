<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';

// Fetch users for personal notification
$users = $conn->query("SELECT id, name, email FROM users");

// Handle form submission
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] === 'all' ? null : intval($_POST['user_id']);
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if ($user_id === null) {
        // Broadcast: insert one row with user_id NULL
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message) VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $title, $message);
    } else {
        // Personal: insert with user_id
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $message);
    }
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Notification sent!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to send notification.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Send Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<div class="container mt-5">
    <h3>Send Notification</h3>
    <?php echo $msg; ?>
    <form method="post">
        <div class="mb-3">
            <label>User</label>
            <select name="user_id" class="form-select" required>
                <option value="all">All Users</option>
                <?php while ($u = $users->fetch_assoc()): ?>
                    <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name'] . ' (' . $u['email'] . ')'); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>
</body>
</html>