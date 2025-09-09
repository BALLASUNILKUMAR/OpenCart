<?php
session_start();
require_once 'includes/db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Store token
        $insert = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $email, $token, $expires_at);
        $insert->execute();

        // Send email (simulate)
        $reset_link = "http://localhost/Sealed Units/reset_password.php?token=" . $token;
        $msg = "<div class='alert alert-success'>Password reset link (copy and paste in browser):<br>
                <a href='$reset_link'>$reset_link</a></div>";
    } else {
        $msg = "<div class='alert alert-danger'>No user found with that email.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/Sealed Units/assets/images/logo.jpeg">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    @media (max-width: 576px) {
      .card.p-4 {
        padding: 1.5rem !important;
      }
    }
  </style>
</head>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-5">
      <div class="card p-4 shadow-sm">
        <h4 class="text-center mb-3">Forgot Password</h4>
        <?php if ($msg) echo $msg; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" required class="form-control">
          </div>
          <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <p class="mt-3 text-center"><a href="login">Back to Login</a></p>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>