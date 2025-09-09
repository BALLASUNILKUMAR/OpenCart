<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';
$user_id = $_SESSION['user_id'];

// Handle address update
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $house_no = trim($_POST['house_no']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $mobile = trim($_POST['mobile']);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);
    $pincode = trim($_POST['pincode']);

    $stmt = $conn->prepare("UPDATE users SET house_no=?, street=?, city=?, mobile=?, state=?, country=?, pincode=? WHERE id=?");
    $stmt->bind_param("sssssssi", $house_no, $street, $city, $mobile, $state, $country, $pincode, $user_id);
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Address updated successfully.</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to update address.</div>';
    }
}



// Fetch user info
$stmt = $conn->prepare("SELECT name, email, house_no, street, city, mobile, state, country, pincode FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Customer Account</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
  <div class="container-fluid mt-4">

  <div class="card p-4 shadow-sm mb-4 w-100">
    <div class="d-flex align-items-center">
      <div class="me-3">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" 
             alt="User Icon" width="60" height="60" class="rounded-circle border">
      </div>
      <div>
        <h4 class="mb-1">Welcome, <?php echo $user ? htmlspecialchars($user['name']) : 'User'; ?></h4>
        <p class="text-muted mb-0"><?php echo $user ? htmlspecialchars($user['email']) : ''; ?></p>
      </div>
    </div>
  </div>

  <?php echo $msg; ?>

  <div class="card p-4 shadow-sm w-100">
    <h5 class="mb-3">Update Your Address</h5>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">House No / Flat No / Office</label>
          <input type="text" class="form-control" name="house_no" 
                 value="<?php echo htmlspecialchars($user['house_no'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Street / Area</label>
          <input type="text" class="form-control" name="street" 
                 value="<?php echo htmlspecialchars($user['street'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">City</label>
          <input type="text" class="form-control" name="city" 
                 value="<?php echo htmlspecialchars($user['city'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Mobile Number</label>
          <input type="text" class="form-control" name="mobile" 
                 value="<?php echo htmlspecialchars($user['mobile'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">State</label>
          <input type="text" class="form-control" name="state" 
                 value="<?php echo htmlspecialchars($user['state'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Country</label>
          <input type="text" class="form-control" name="country" 
                 value="<?php echo htmlspecialchars($user['country'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Pincode</label>
          <input type="text" class="form-control" name="pincode" 
                 value="<?php echo htmlspecialchars($user['pincode'] ?? '', ENT_QUOTES); ?>" required>
        </div>
      </div>
      <button type="submit" name="update_address" class="btn btn-primary mt-3">Update Address</button>
    </form>
  </div>
</div>
  <!-- Footer -->
 <?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>