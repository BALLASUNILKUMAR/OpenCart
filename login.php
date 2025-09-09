<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/Sealed Units/assets/images/logo.jpeg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h4 class="text-center mb-3">Login</h4>
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="process/login.php" method="POST">
          <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
          <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center"><a href="forgot_password">Forgot Password?</a></p>
        <p class="mt-3 text-center">Don't have an account? <a href="register">Register</a></p>
      </div>
    </div>
  </div>
</div>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>
</body>
</html>