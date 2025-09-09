<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light shadow-sm">

  <div class="container">
    <a class="navbar-brand" href="/Sealed Units/index.php">
      <img src="/Sealed Units/assets/images/logo.jpeg" alt="GlassCalc Logo" style="height:40px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
      aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/Sealed Units/index">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/Sealed Units/delivery">Delivery</a></li>
        <li class="nav-item"><a class="nav-link" href="/Sealed Units/glass">Product</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'customer'): ?>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/user/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/user/orders.php">Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/user/notify.php">Notifications</a></li>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/admin/customers.php">Admin Panel</a></li>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/admin/enquires.php">Enquires</a></li>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/admin/notifications.php">Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="/Sealed Units/admin/dashboard.php">Dashboard</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link text-danger" href="/Sealed Units/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/Sealed Units/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>