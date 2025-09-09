<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Your Cart</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/Sealed Units/assets/images/logo.jpeg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @keyframes cart-move {
      0%   { transform: translateX(-40px); }
      50%  { transform: translateX(40px); }
      100% { transform: translateX(-40px); }
    }
    .cart-animate {
      display: inline-block;
      animation: cart-move 2s infinite;
    }
    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.95rem;
      }
      .cart-animate i {
        font-size: 3rem !important;
      }
    }
  </style>
  <!-- Bootstrap Icons CDN for cart icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
</body>
</html>