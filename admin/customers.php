<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

require_once '../includes/db.php';


$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$total_income = $conn->query("SELECT SUM(total) FROM orders")->fetch_row()[0];
$total_users = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetch_row()[0];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Customer Statistics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../includes/navbar.php'; ?>
<div class="container mt-5">
  <div class="row g-3 mt-3">
    <div class="col-md-4"><div class="card p-3 bg-success text-white">Total Orders: <?= $total_orders ?></div></div>
    <div class="col-md-4"><div class="card p-3 bg-primary text-white">Total Income: ₹<?= $total_income ?: 0 ?></div></div>
    <div class="col-md-4"><div class="card p-3 bg-warning text-dark">Total Users: <?= $total_users ?></div></div>
  </div>
</div>
<!-- Add this before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
