<?php
session_start();
require_once 'includes/db.php';

// ✅ Step 1: Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

// ✅ Step 2: Fetch user details
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT name, house_no, street, city, state, country, pincode, mobile 
                        FROM users WHERE id = $user_id LIMIT 1");
$user = $result->fetch_assoc();

// ✅ Step 3: Redirect if address incomplete
if (empty($user['house_no']) || empty($user['city']) || empty($user['pincode'])) {
    header("Location: dashboard.php?update_address=1");
    exit();
}

// ✅ Step 4: Load cart from session (you may store cart in session or database)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="stylesheet" href="styles.css"> <!-- your CSS -->
</head>
<body>
  <h2>Checkout</h2>

  <!-- ✅ Show Address -->
  <h3>Delivery Address</h3>
  <p><strong>Name :<?php echo htmlspecialchars($user['name']); ?></strong></p>
  <p><?php echo htmlspecialchars($user['house_no'] . ', ' . $user['street']); ?></p>
  <p><?php echo htmlspecialchars($user['city'] . ', ' . $user['state']); ?> - <?php echo htmlspecialchars($user['pincode']); ?></p>
  <p><?php echo htmlspecialchars($user['country']); ?></p>
  <p>Mobile: <?php echo htmlspecialchars($user['mobile']); ?></p>
  <a href="dashboard.php?update_address=1">Edit Address</a>

  <hr>

  <!-- ✅ Show Cart -->
  <h3>Order Summary</h3>
  <?php if (empty($cart)): ?>
      <p>Your cart is empty. <a href="index.php">Continue Shopping</a></p>
  <?php else: ?>
      <table border="1" cellpadding="8">
        <tr>
          <th>Product</th>
          <th>Dimensions</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Total</th>
        </tr>
        <?php
          $subtotal = 0;
          foreach ($cart as $item):
              $total = $item['unitPrice'] * $item['quantity'];
              $subtotal += $total;
        ?>
        <tr>
          <td><?php echo ucfirst($item['type']); ?></td>
          <td><?php echo $item['details']['width'] . "mm × " . $item['details']['height'] . "mm"; ?></td>
          <td><?php echo $item['quantity']; ?></td>
          <td>£<?php echo number_format($item['unitPrice'], 2); ?></td>
          <td>£<?php echo number_format($total, 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </table>

      <?php
        $vat = $subtotal * 0.20;
        $totalAmount = $subtotal + $vat;
      ?>
      <p><strong>Subtotal:</strong> £<?php echo number_format($subtotal, 2); ?></p>
      <p><strong>VAT (20%):</strong> £<?php echo number_format($vat, 2); ?></p>
      <p><strong>Total:</strong> £<?php echo number_format($totalAmount, 2); ?></p>

      <!-- ✅ Confirm purchase -->
      <form action="payment.php" method="post">
        <button type="submit">Proceed to Payment</button>
      </form>
  <?php endif; ?>
</body>
</html>
