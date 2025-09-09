<?php
session_start();
require_once 'includes/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired token.");
    }

    $data = $result->fetch_assoc();
    if (strtotime($data['expires_at']) < time()) {
        die("Token expired.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_pass = $_POST['password'];
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

        // Update user's password
        $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->bind_param("ss", $hashed, $data['email']);
        $update->execute();

        // Delete used token
        $del = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $del->bind_param("s", $data['email']);
        $del->execute();

        echo "<p>Password reset successful. <a href='login.php'>Login Now</a></p>";
        exit();
    }
} else {
    die("Token missing.");
}
?>

<!-- Reset Password Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
  <h2>Reset Password</h2>
  <form method="POST">
    <div class="mb-3">
      <label>New Password</label>
      <input type="password" name="password" required class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Update Password</button>
  </form>
</body>
</html>
